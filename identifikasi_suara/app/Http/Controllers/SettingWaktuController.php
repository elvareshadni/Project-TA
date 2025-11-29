<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingWaktuController extends Controller
{
    public function index()
    {
        return view('admin.setting-waktu');
    }
}
