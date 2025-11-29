<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $username = $request->input('username');
    $password = $request->input('password');

    // Contoh validasi sederhana
    if ($username === 'admin' && $password === '12345') {
        session(['username' => $username]); // simpan ke session
        return redirect('/home');
    }

    return back()->with('error', 'Username atau password salah!');
}
}
