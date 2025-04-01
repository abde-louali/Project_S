<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function index()
    {
        if (!session()->has('admin')) {
            return redirect('/login');
        }
        return view('Admin');
    }

    public function Adminprofile()
    {
        if (!session()->has('admin')) {
            return redirect('/login');
        }

        $admin = Session::get('admin');
        $adminModel = Admin::where('username', $admin['username'])->first();

        return view('Adminprofile', compact('adminModel'));
    }

    public function updateProfile(Request $request)
    {
        if (!session()->has('admin')) {
            return redirect('/login');
        }

        $admin = Session::get('admin');
        $adminModel = Admin::where('username', $admin['username'])->first();

        $validatedData = $request->validate([
            'username' => 'required|string|max:255|unique:admins,username,' . $adminModel->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'cin' => 'required|string|max:20|unique:admins,cin,' . $adminModel->id,
        ]);

        $adminModel->update($validatedData);

        // Update session data
        $admin['username'] = $validatedData['username'];
        Session::put('admin', $admin);

        return redirect('/admin/profile')->with([
            'status' => 'success',
            'message' => 'Profile updated successfully'
        ]);
    }

    public function changePassword(Request $request)
    {
        if (!session()->has('admin')) {
            return redirect('/login');
        }

        $request->validate([
            'currentPassword' => 'required',
            'newPassword' => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).+$/'
            ],
            'confirmPassword' => 'required|same:newPassword'
        ], [
            'newPassword.regex' => 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial'
        ]);

        $admin = Session::get('admin');
        $adminModel = Admin::where('username', $admin['username'])->first();

        // Verify the current password
        if (!Hash::check($request->currentPassword, $adminModel->password)) {
            return back()->with([
                'status' => 'error',
                'message' => 'Le mot de passe actuel est incorrect'
            ]);
        }

        // Hash the new password before saving
        $adminModel->password = Hash::make($request->newPassword);
        $adminModel->save();

        return redirect('/admin/profile')->with([
            'status' => 'success',
            'message' => 'Mot de passe modifié avec succès'
        ]);
    }
}
