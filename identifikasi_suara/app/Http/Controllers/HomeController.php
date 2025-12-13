<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SettingWaktu;

class HomeController extends Controller
{
    public function index()
    {
        return redirect()->route('home.dashboard');
    }
    public function dashboard()
    {
        if (!session('username')) {
            return redirect()->route('informasi');
        }

        $username = session('username', 'Pengguna');

        $setting = SettingWaktu::first();
        $maxSeconds = 5 * 60; // Default max 5 menit
        $minSeconds = 3 * 60; // Default min 3 menit

        if ($setting) {
            if ($setting->durasi === '3-5') {
                $minSeconds = 3 * 60;
                $maxSeconds = 5 * 60;
            } elseif ($setting->durasi === '9-10') {
                $minSeconds = 9 * 60;
                $maxSeconds = 10 * 60;
            }
        }

        return view('home.index', compact('username', 'maxSeconds', 'minSeconds'));
    }
}
