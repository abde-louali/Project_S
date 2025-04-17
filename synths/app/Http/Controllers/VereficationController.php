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
}
