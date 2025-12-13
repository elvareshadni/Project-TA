<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\RekapDataController;
use App\Http\Controllers\SettingWaktuController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HasilIdentifikasiController;

// Halaman awal → Form informasi user biasa
Route::get('/', function () {
    return view('auth.informasi');
})->name('informasi');

Route::view('/tentang-kami', 'tentang-kami')->name('tentang-kami');

// Halaman Home setelah informasi
Route::get('/home', [HomeController::class, 'dashboard'])->name('home.dashboard');

// Login admin
Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AdminController::class, 'login'])->name('auth.login.submit');

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
        'redirect' => route('home.dashboard'),
    ]);
})->name('save.user');

// Simpan HASIL IDENTIFIKASI (dipanggil dari JS di home)
Route::post('/identifikasi/simpan', [HasilIdentifikasiController::class, 'simpan'])
    ->name('identifikasi.simpan');

// Group route ADMIN (harus login guard:admin)
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/admin/rekap-data', [RekapDataController::class, 'index'])
        ->name('admin.rekap-data.index');

    Route::get('/admin/rekap-data/{id}/pdf', [RekapDataController::class, 'printPdf'])
        ->name('admin.rekap-data.pdf');

    Route::delete('/admin/rekap-data/{id}', [RekapDataController::class, 'destroy'])
        ->name('admin.rekap-data.destroy');

    Route::get('/setting-waktu', [SettingWaktuController::class, 'index'])
        ->name('admin.setting-waktu.index');

    Route::post('/setting-waktu', [SettingWaktuController::class, 'update'])
        ->name('admin.setting-waktu.update');
});

Route::post('/logout', function () {
    session()->flush();
    return redirect()->route('informasi');
})->name('logout');
