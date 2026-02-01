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

        if ($setting && $setting->durasi) {
            $parts = explode('-', $setting->durasi);
            if (count($parts) == 2) {
                $minSeconds = (int)$parts[0] * 60;
                $maxSeconds = (int)$parts[1] * 60;
            }
        }

        return view('home.index', compact('username', 'maxSeconds', 'minSeconds'));
    }
}
