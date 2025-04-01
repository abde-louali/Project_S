<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;
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
        $classe = Classe::where('cin', $cin)->first();

        if ($classe) {
            $classe->delete();
            return redirect()->back()->with('success', 'Student deleted successfully!');
        }

        return redirect()->back()->with('error', 'Student not found.');
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
