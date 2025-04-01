<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Illuminate\Support\Facades\Http;

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
                // Store the results in the session for the results page
                session(['verification_results' => $response->json()]);

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
}
