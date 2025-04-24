<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Student;
use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;


class ClasseController extends Controller
{

    public function check()
    {
        if (!session()->has('admin')) {
            return redirect('/login');
        }

        $classes = Classe::orderBy('filier_name')->orderBy('code_class')->paginate(10);
        return view('Ajouterclass', compact('classes'));
    }


    /**
     * Handle file upload and import data from Excel.
     */
    public function import(Request $request)
    {
        if (!session()->has('admin')) {
            return redirect('/login');
        }
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('excel_file');
        $extension = $file->getClientOriginalExtension();

        if ($extension == 'xlsx' || $extension == 'xls') {
            try {
                // Load the Excel file
                $spreadsheet = IOFactory::load($file->getPathname());
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray();

                // Skip the header row (first row)
                $header = array_shift($data);

                // Process each row of the Excel file
                foreach ($data as $row) {
                    // Skip empty rows or rows with missing data
                    if (empty($row[0])) {
                        continue;
                    }

                    // Insert or update the record in the database
                    Classe::updateOrCreate(
                        ['cin' => $row[2]], // Unique identifier (CIN)
                        [
                            'code_class' => $row[0],
                            'filier_name' => $row[1],
                            's_fname' => $row[3],
                            's_lname' => $row[4],
                            'age' => (int)$row[5], // Ensure age is cast to integer
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }

                return redirect()->back()->with('success', 'Data imported successfully!');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Error processing the file: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Invalid file format. Please upload an Excel file (xlsx or xls).');
        }
    }

    /**
     * Delete a student record.
     */
    public function destroy($cin)
    {
        if (!session()->has('admin')) {
            return redirect('/login');
        }

        try {
            // Find the student in the classes table
            $classe = Classe::where('cin', $cin)->first();

            if (!$classe) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Student not found.'
                    ]);
                }

                return redirect()->back()->with('error', 'Student not found.');
            }

            // Find and delete the corresponding student record in the students table
            $student = Student::where('cin', $cin)->first();
            if ($student) {
                // Delete student's document files if they exist
                if ($student->id_card_img && file_exists(storage_path('app/public/' . $student->id_card_img))) {
                    Storage::delete('public/' . $student->id_card_img);
                }

                if ($student->bac_img && file_exists(storage_path('app/public/' . $student->bac_img))) {
                    Storage::delete('public/' . $student->bac_img);
                }

                if ($student->birth_img && file_exists(storage_path('app/public/' . $student->birth_img))) {
                    Storage::delete('public/' . $student->birth_img);
                }

                // Delete student folder if it exists
                $folderPath = public_path("uploads/{$student->filier_name}/{$student->code_class}/{$student->cin}_{$student->s_lname}");
                if (file_exists($folderPath) && is_dir($folderPath)) {
                    $this->deleteDirectory($folderPath);
                }

                // Delete the student record
                $student->delete();
            }

            // Delete the class record
            $classe->delete();

            // Check if this is an AJAX request
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Student deleted successfully from both classes and students tables!'
                ]);
            }

            return redirect()->back()->with('success', 'Student deleted successfully from both classes and students tables!');
        } catch (\Exception $e) {
            // Handle any exceptions that might occur
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting student: ' . $e->getMessage()
                ]);
            }

            return redirect()->back()->with('error', 'Error deleting student: ' . $e->getMessage());
        }
    }

    /**
     * Helper method to recursively delete a directory
     */
    private function deleteDirectory($dir)
    {
        if (!file_exists($dir) || !is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }

        return rmdir($dir);
    }

    public function filiers()
    {
        if (!session()->has('admin')) {
            return redirect('/login');
        }
        $filiers = Classe::distinct()->pluck('filier_name');

        // Fetch all classes with their filier names
        $classes = Classe::select('filier_name', 'code_class')
            ->get()
            ->groupBy('filier_name')
            ->toArray();

        return view('filiers')
            ->with('filiers', $filiers)
            ->with('classesData', json_encode($classes));
    }

    // public function getgroups($filierName)
    // {
    //     $classes = Classe::table('classes')
    //         ->select('code_class')
    //         ->where('filier_name', $filierName)
    //         ->distinct()
    //         ->get();

    //     return response()->json($classes);
    // }
}
