<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('Login');
    }

    public function check(Request $req)
    {
        $req->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Check admin login
        $admin = Admin::where('username', $req->username)->first();

        if ($admin && Hash::check($req->password, $admin->password)) {
            session()->put('admin', $admin);
            return redirect('/admin');
        }

        // Check student login
        $student = Classe::where('cin', $req->username)->first();

        if ($student && $student->cin == $req->password) {
            session()->put('student', $student);
            return redirect('/student');
        }

        // If neither matches
        return back()->withErrors([
            'login_error' => 'Invalid username or password',
        ])->withInput();
    }

    public function logout()
    {
        session()->forget('student');
        session()->forget('admin');
        return redirect('/login');
    }
}
