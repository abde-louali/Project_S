<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        if (!session()->has('student')) {
            return redirect('/login');
        }
        return view('student');
    }

    public function poststg(Request $request)
    {
        // Validate the request with custom error messages
        $validated = $request->validate([
            'cin' => 'required|string|max:40',
            's_fname' => 'required|string|max:100',
            's_lname' => 'required|string|max:100',
            'code_class' => 'required|string|max:50',
            'filier_name' => 'required|string|max:40',
            'id_card_img' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'bac_img' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'birth_img' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ], [
            'code_class.required' => 'The class field is required.',
            'filier_name.required' => 'The filier field is required.',
            'id_card_img.required' => 'The ID card image is required.',
            'bac_img.required' => 'The baccalaureate certificate is required.',
            'birth_img.required' => 'The birth certificate is required.',
            '*.mimes' => 'The file must be a valid image (jpeg, png, jpg) or PDF.',
            '*.max' => 'The file size must not exceed 2MB.',
        ]);

        try {
            $student = Student::where('cin', $request->cin)->first();
            if (!$student) {
                $student = new Student();
            }

            $student->s_fname = $request->s_fname;
            $student->s_lname = $request->s_lname;
            $student->cin = $request->cin;
            $student->code_class = $request->code_class;
            $student->filier_name = $request->filier_name;

            // Process the image uploads
            $this->processImageUploads($request, $student);

            $student->save();

            return redirect('/student')->with([
                'success' => 'Your documents have been submitted successfully!',
                'student' => $student // Keep student in session
            ]);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors([
                'error' => 'An error occurred while saving your documents. Please try again.'
            ]);
        }
    }

    private function processImageUploads(Request $request, Student $student)
    {
        // Process ID Card Image
        if ($request->hasFile('id_card_img')) {
            $file = $request->file('id_card_img');
            $filename = 'id_card_img.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('students/' . $student->cin, $filename, 'public');
            $student->id_card_img = $path;
        }

        // Process BAC Image
        if ($request->hasFile('bac_img')) {
            $file = $request->file('bac_img');
            $filename = 'bac_img.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('students/' . $student->cin, $filename, 'public');
            $student->bac_img = $path;
        }

        // Process Birth Certificate Image
        if ($request->hasFile('birth_img')) {
            $file = $request->file('birth_img');
            $filename = 'birth_img.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('students/' . $student->cin, $filename, 'public');
            $student->birth_img = $path;
        }
    }
}
