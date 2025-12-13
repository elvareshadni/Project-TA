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

        if ($username === 'admin' && $password === '12345') {
            session(['username' => $username]);
            return redirect('/home');
        }

        return back()->with('error', 'Username atau password salah!');
    }

    public function saveUser(Request $request)
    {
        // simpan data ke session
        session([
            'nama'   => $request->nama,
            'email'  => $request->email,
            'gender' => $request->gender,
            'usia'   => $request->usia,
        ]);

        return response()->json([
            'status'   => 'success',
            'redirect' => '/home/dashboard',  
        ]);
    }
}
