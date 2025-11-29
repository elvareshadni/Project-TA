<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $username = session('username', 'Pengguna');
        return view('home', compact('username'));
    }
}
