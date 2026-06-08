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
        $maxSeconds = 300; // Default max 300 detik (5 menit)
        $minSeconds = 180; // Default min 180 detik (3 menit)

        if ($setting && $setting->durasi) {
            $parts = explode('-', $setting->durasi);
            if (count($parts) == 2) {
                $minVal = (int)$parts[0];
                $maxVal = (int)$parts[1];
                if ($maxVal < 60) {
                    $minSeconds = $minVal * 60;
                    $maxSeconds = $maxVal * 60;
                } else {
                    $minSeconds = $minVal;
                    $maxSeconds = $maxVal;
                }
            }
        }

        return view('home.index', compact('username', 'maxSeconds', 'minSeconds'));
    }
}
