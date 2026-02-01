<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SettingWaktu;

class SettingWaktuController extends Controller
{
    public function index()
    {
        $setting = SettingWaktu::first();
        return view('admin.setting-waktu', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'durasi_min' => 'required|numeric|min:1',
            'durasi_max' => 'required|numeric|gt:durasi_min',
        ]);

        $setting = SettingWaktu::first() ?? new SettingWaktu();
        $setting->durasi = $request->durasi_min . '-' . $request->durasi_max;
        $setting->save();

        return back()->with('success', 'Durasi identifikasi berhasil disimpan.');
    }
}
