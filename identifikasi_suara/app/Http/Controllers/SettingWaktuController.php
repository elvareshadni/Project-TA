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
            'durasi' => 'required|in:1-2,3-5,9-10',
        ]);

        $setting = SettingWaktu::first() ?? new SettingWaktu();
        $setting->durasi = $request->durasi;
        $setting->save();

        return back()->with('success', 'Durasi identifikasi berhasil disimpan.');
    }
}
