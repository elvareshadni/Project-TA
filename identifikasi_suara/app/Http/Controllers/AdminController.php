<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string', // ini boleh nama atau email
            'password' => 'required|string',
        ]);

        $loginInput = $request->input('email');
        $password   = $request->input('password');

        // Kalau format email → pakai kolom email, kalau bukan → pakai kolom nama (sesuai tabel)
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'nama';

        if (Auth::guard('admin')->attempt([
            $fieldType => $loginInput,
            'password' => $password,
        ])) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()
            ->withErrors(['email' => 'Username atau password salah.'])
            ->withInput($request->only('email'));
    }

    public function dashboard()
    {
        return view('admin.index'); 
    }
}
