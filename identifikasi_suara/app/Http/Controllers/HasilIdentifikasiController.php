<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilIdentifikasi;
use Illuminate\Support\Facades\Http;

class HasilIdentifikasiController extends Controller
{
    public function simpan(Request $request)
    {
        $request->validate([
            'file_suara' => 'required|file|mimes:wav,mp3',
        ]);

        // Simpan file suara ke storage
        $file = $request->file('file_suara');
        $namaFile = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/suara', $namaFile);

        // Kirim audio ke FastAPI
        $response = Http::attach(
            'file',
            file_get_contents($file),
            $file->getClientOriginalName()
        )->post('http://localhost:8001/predict'); // Ganti sesuai endpoint kamu

        // Ambil hasil dari FastAPI
        $hasil = $response->json()['emotion'] ?? 'Tidak terdeteksi';
        $akurasi = $response->json()['accuracy'] ?? null;

        // Simpan ke database
        HasilIdentifikasi::create([
            'user_id'   => auth()->id(),
            'file_suara'=> $namaFile,
            'hasil'     => $hasil,
            'akurasi'   => $akurasi,
        ]);

        return redirect()->back()->with('success', 'Hasil identifikasi berhasil disimpan!');
    }
}
