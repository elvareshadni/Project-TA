<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\RekapDataController;
use App\Http\Controllers\SettingWaktuController;

// Halaman awal → Form informasi user biasa
Route::get('/', function () {
    return view('auth.informasi');
})->name('informasi');

Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AdminController::class, 'login'])->name('auth.login.submit');

// Group route admin (hanya untuk admin yang sudah login pakai guard:admin)
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/admin/rekap-data', [RekapDataController::class, 'index'])
        ->name('admin.rekap-data.index');
    Route::get('/setting-waktu', [SettingWaktuController::class, 'index'])
    ->name('admin.setting-waktu.index');

Route::post('/identifikasi/simpan', [HasilIdentifikasiController::class, 'simpan'])
    ->name('identifikasi.simpan')
    ->middleware('auth');

});

// Simpan data user dari form informasi
Route::post('/save-user', function (Request $request) {
    $validated = $request->validate([
        'nama'   => 'required|string|max:100',
        'email'  => 'required|email',
        'gender' => 'required|string',
        'usia'   => 'required|numeric',
    ]);

    session([
        'username' => $validated['nama'],
        'email'    => $validated['email'],
        'gender'   => $validated['gender'],
        'usia'     => $validated['usia'],
    ]);

    return response()->json([
        'status'   => 'success',
        'redirect' => route('home'),
    ]);
});

// Halaman Home setelah informasi
Route::get('/home', function () {
    if (!session('username')) {
        return redirect()->route('informasi');
    }
    return view('home');
})->name('home');

// Logout user biasa
Route::post('/logout', function () {
    session()->flush();
    return redirect()->route('informasi');
})->name('logout');
