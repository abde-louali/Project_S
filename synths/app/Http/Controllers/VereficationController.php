<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\DocumentValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class VereficationController extends Controller
{
    public function verifyDocuments(Request $request, $filierName, $className)
    {
        if (!session()->has('admin')) {
            return redirect('/login');
        }
        try {
            ini_set('max_execution_time', '900');
            set_time_limit(900);

            // Use the original folder name with spaces
            $folderPath = public_path('uploads/' . $filierName . '/' . $className);

            // Check if the directory exists
            if (!is_dir($folderPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Directory not found: ' . $folderPath
                ]);
            }

            // 3. Create a temporary zip file
            $tempDir = sys_get_temp_dir();
            $zipFilePath = $tempDir . DIRECTORY_SEPARATOR . uniqid('upload_') . '.zip';

            if (file_exists($zipFilePath)) {
                @unlink($zipFilePath);
            }

            $zip = new ZipArchive();
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create zip file'
                ]);
            }

            // 4. Add files to the zip
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($folderPath),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            $fileCount = 0;
            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($folderPath) + 1);

                    if (!$zip->addFile($filePath, $relativePath)) {
                        $zip->close();
                        @unlink($zipFilePath);
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to add file to zip'
                        ]);
                    }
                    $fileCount++;
                }
            }

            if ($fileCount == 0) {
                $zip->close();
                @unlink($zipFilePath);
                return response()->json([
                    'success' => false,
                    'message' => 'No files found in the directory to zip'
                ]);
            }

            $zip->close();

            // 5. Send the zip file to the Python API
            $url = 'http://localhost:81/validate';

            $response = Http::timeout(300)
                ->attach('file', file_get_contents($zipFilePath), basename($zipFilePath))
                ->post($url);

            // 6. Clean up the temporary zip file
            if (file_exists($zipFilePath)) {
                usleep(100000); // Small delay to ensure file handle is released
                @unlink($zipFilePath);
            }

            // 7. Check the response
            if ($response->successful()) {
                $results = $response->json();

                // Store the results in the session for the results page
                session(['verification_results' => $results]);

                // Store the results in the database
                $this->saveValidationResults($results, $filierName, $className);

                return response()->json([
                    'success' => true
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to process verification. API returned error.'
                ]);
            }
        } catch (\Exception $e) {
            // If any exception occurs, return an error response
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Save document validation results to the database
     */
    private function saveValidationResults($results, $filierName, $className)
    {
        if (empty($results)) {
            return;
        }

        foreach ($results as $result) {
            try {
                // Find the student by CIN
                $student = Student::where('cin', $result['cin'])->first();

                // Create validation record
                DocumentValidation::create([
                    'student_id' => $student ? $student->id : null,
                    'cin' => $result['cin'],
                    'student_name' => $student ? $student->s_fname . ' ' . $student->s_lname : 'Unknown',
                    'verified_name' => $result['verified_name'] ?? null,
                    'is_correct' => $result['is_correct'] ?? false,
                    'file_details' => $result['files_processed'] ?? null,
                    'errors' => isset($result['errors']) ? $result['errors'] : null,
                    'filier_name' => $filierName,
                    'class_name' => $className,
                    'validation_date' => Carbon::now()
                ]);
            } catch (\Exception $e) {
                // Log error but continue with other results
                Log::error('Error saving validation result: ' . $e->getMessage());
                continue;
            }
        }
    }

    public function showResults($filierName, $className)
    {
        if (!session()->has('admin')) {
            return redirect('/login');
        }

        // Get the verification results from the session
        $results = session('verification_results', []);

        // Get students data for this class
        $students = Student::where('code_class', $className)
            ->where('filier_name', $filierName)
            ->get();

        return view('document-verification-results', [
            'results' => $results,
            'students' => $students,
            'filierName' => $filierName,
            'className' => $className
        ]);
    }

    /**
     * Show all validated documents history
     */
    public function validationHistory()
    {
        if (!session()->has('admin')) {
            return redirect('/login');
        }

        // Get all validation records, grouped by filier and class
        $validations = DocumentValidation::orderBy('created_at', 'desc')
            ->get()
            ->groupBy(['filier_name', 'class_name']);

        return view('validation-history', [
            'validations' => $validations
        ]);
    }

    /**
     * Show validation details for a specific class
     */
    public function validationDetails($filierName, $className)
    {
        if (!session()->has('admin')) {
            return redirect('/login');
        }

        // Get validation records for this class
        $validations = DocumentValidation::where('filier_name', $filierName)
            ->where('class_name', $className)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get students data for this class
        $students = Student::where('code_class', $className)
            ->where('filier_name', $filierName)
            ->get();

        return view('validation-details', [
            'validations' => $validations,
            'students' => $students,
            'filierName' => $filierName,
            'className' => $className
        ]);
    }

    /**
     * Delete a single validation record
     */
    public function deleteValidation($id)
    {
        if (!session()->has('admin')) {
            return redirect('/login');
        }

        $validation = DocumentValidation::findOrFail($id);
        $filierName = $validation->filier_name;
        $className = $validation->class_name;

        $validation->delete();

        return redirect()
            ->route('validation.details', ['filierName' => $filierName, 'className' => $className])
            ->with('success', 'Validation record has been deleted successfully.');
    }

    /**
     * Delete all validation records for a specific class
     */
    public function deleteAllValidations($filierName, $className)
    {
        if (!session()->has('admin')) {
            return redirect('/login');
        }

        DocumentValidation::where('filier_name', $filierName)
            ->where('class_name', $className)
            ->delete();

        return redirect()
            ->route('validation.history')
            ->with('success', 'All validation records for ' . $filierName . ' / ' . $className . ' have been deleted successfully.');
    }

    /**
     * Export validation history as Excel, CSV, or Text file
     */
    public function exportValidationHistory(Request $request)
    {
        if (!session()->has('admin')) {
            return redirect('/login');
        }

        // Get the requested format (default to excel)
        $format = $request->query('format', 'excel');

        // Get all validation records
        $validations = DocumentValidation::orderBy('filier_name')
            ->orderBy('class_name')
            ->orderBy('created_at', 'desc')
            ->get();

        // Generate filename based on format
        $timestamp = date('Y-m-d_H-i-s');

        switch ($format) {
            case 'csv':
                return $this->exportAsCsv($validations, "validation_history_{$timestamp}.csv");
            case 'text':
                return $this->exportAsText($validations, "validation_history_{$timestamp}.txt");
            case 'excel':
            default:
                return $this->exportAsExcel($validations, "validation_history_{$timestamp}.xlsx");
        }
    }

    /**
     * Export data as Excel
     */
    private function exportAsExcel($validations, $filename)
    {
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set column headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Filier');
        $sheet->setCellValue('C1', 'Class');
        $sheet->setCellValue('D1', 'CIN');
        $sheet->setCellValue('E1', 'Student Name');
        $sheet->setCellValue('F1', 'Verified Name');
        $sheet->setCellValue('G1', 'Status');
        $sheet->setCellValue('H1', 'Validation Date');
        $sheet->setCellValue('I1', 'Created At');

        // Style the header row
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'],
            ],
        ];

        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

        // Add data rows
        $row = 2;
        foreach ($validations as $validation) {
            $sheet->setCellValue('A' . $row, $validation->id);
            $sheet->setCellValue('B' . $row, $validation->filier_name);
            $sheet->setCellValue('C' . $row, $validation->class_name);
            $sheet->setCellValue('D' . $row, $validation->cin);
            $sheet->setCellValue('E' . $row, $validation->student_name);
            $sheet->setCellValue('F' . $row, $validation->verified_name);
            $sheet->setCellValue('G' . $row, $validation->is_correct ? 'Correct' : 'Incorrect');
            $sheet->setCellValue('H' . $row, $validation->validation_date ? $validation->validation_date->format('Y-m-d H:i:s') : '');
            $sheet->setCellValue('I' . $row, $validation->created_at->format('Y-m-d H:i:s'));

            // Apply row styling based on validation status
            if ($validation->is_correct) {
                $sheet->getStyle('G' . $row)->applyFromArray([
                    'font' => ['color' => ['rgb' => '047857']]
                ]);
            } else {
                $sheet->getStyle('G' . $row)->applyFromArray([
                    'font' => ['color' => ['rgb' => 'DC2626']]
                ]);
            }

            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'I') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Create the Excel file
        $writer = new Xlsx($spreadsheet);

        // Save to a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'export');
        $writer->save($tempFile);

        // Return the file as a download
        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Export data as CSV
     */
    private function exportAsCsv($validations, $filename)
    {
        // Create temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'export');
        $handle = fopen($tempFile, 'w');

        // Add UTF-8 BOM to ensure proper encoding
        fputs($handle, "\xEF\xBB\xBF");

        // Add headers
        fputcsv($handle, [
            'ID',
            'Filier',
            'Class',
            'CIN',
            'Student Name',
            'Verified Name',
            'Status',
            'Validation Date',
            'Created At'
        ]);

        // Add data rows
        foreach ($validations as $validation) {
            fputcsv($handle, [
                $validation->id,
                $validation->filier_name,
                $validation->class_name,
                $validation->cin,
                $validation->student_name,
                $validation->verified_name,
                $validation->is_correct ? 'Correct' : 'Incorrect',
                $validation->validation_date ? $validation->validation_date->format('Y-m-d H:i:s') : '',
                $validation->created_at->format('Y-m-d H:i:s')
            ]);
        }

        fclose($handle);

        // Return the file as a download
        return response()->download($tempFile, $filename, [
            'Content-Type' => 'text/csv',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Export data as plain text
     */
    private function exportAsText($validations, $filename)
    {
        // Create temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'export');
        $handle = fopen($tempFile, 'w');

        // Write headers with formatting
        $headers = "ID\tFilier\tClass\tCIN\tStudent Name\tVerified Name\tStatus\tValidation Date\tCreated At\n";
        $separator = str_repeat('-', 120) . "\n";

        fwrite($handle, $headers);
        fwrite($handle, $separator);

        // Write data rows
        foreach ($validations as $validation) {
            $row = implode("\t", [
                $validation->id,
                $validation->filier_name,
                $validation->class_name,
                $validation->cin,
                $validation->student_name,
                $validation->verified_name ?? 'N/A',
                $validation->is_correct ? 'Correct' : 'Incorrect',
                $validation->validation_date ? $validation->validation_date->format('Y-m-d H:i:s') : 'N/A',
                $validation->created_at->format('Y-m-d H:i:s')
            ]) . "\n";

            fwrite($handle, $row);
        }

        fclose($handle);

        // Return the file as a download
        return response()->download($tempFile, $filename, [
            'Content-Type' => 'text/plain',
        ])->deleteFileAfterSend(true);
    }
}
