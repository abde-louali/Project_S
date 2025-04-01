<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class FoldersController extends Controller
{
    public function groupdetails($filierName, $className)
    {
        if (!session()->has('admin')) {
            return redirect('/login');
        }
        $classDetails = Student::where('filier_name', $filierName)
            ->where('code_class', $className)
            ->get();

        return view('Classdetails', [
            'filierName' => $filierName,
            'className' => $className,
            'students' => $classDetails
        ]);
    }

    public function createFolders($filierName, $className)
    {

        try {
            // Get all students from the specified class
            $students = Student::where('filier_name', $filierName)
                ->where('code_class', $className)
                ->get();

            $createdFolders = 0;

            // Loop through students and create their folders
            foreach ($students as $student) {
                // Create student directory path
                $studentPath = public_path("uploads/{$filierName}/{$className}/{$student->cin}_{$student->s_lname}");

                if (!file_exists($studentPath)) {
                    mkdir($studentPath, 0755, true);
                    $createdFolders++;
                }

                // Copy files from storage to public folder
                $this->copyFileToPublic($student->id_card_img, $studentPath, 'id_card_img');
                $this->copyFileToPublic($student->bac_img, $studentPath, 'bac_img');
                $this->copyFileToPublic($student->birth_img, $studentPath, 'birth_img');
            }

            return response()->json([
                'success' => true,
                'message' => 'Folders created successfully',
                'count' => $createdFolders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function copyFileToPublic($filePath, $destinationPath, $defaultName)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $filename = "{$defaultName}.{$extension}";
            $destination = "{$destinationPath}/{$filename}";

            copy(storage_path("app/public/{$filePath}"), $destination);
        }
    }
}
