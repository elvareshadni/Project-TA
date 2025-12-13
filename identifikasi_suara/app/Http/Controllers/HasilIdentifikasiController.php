<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilIdentifikasi;

class HasilIdentifikasiController extends Controller
{
    public function simpan(Request $request)
    {
        $data = $request->validate([
            'sumber'    => 'required|in:upload,record',
            'file_suara'=> 'nullable|string',
            'hasil'     => 'required|string',
            'akurasi'   => 'nullable|numeric',

            'distribution_by_emotion' => 'nullable',
            'distribution_by_suku'    => 'nullable',

            'nama'   => 'nullable|string',
            'email'  => 'nullable|email',
            'gender' => 'nullable|string',
            'usia'   => 'nullable',
        ]);

        HasilIdentifikasi::create([
            'nama'   => $data['nama']   ?? null,
            'email'  => $data['email']  ?? null,
            'gender' => $data['gender'] ?? null,
            'usia'   => $data['usia']   ?? null,

            'sumber'     => $data['sumber'],
            'file_suara' => $data['file_suara'] ?? null,
            'hasil'      => $data['hasil'],
            'akurasi'    => $data['akurasi'] ?? null,

            'distribution_by_emotion' => $data['distribution_by_emotion'] ?? null,
            'distribution_by_suku'    => $data['distribution_by_suku'] ?? null,
        ]);

        return response()->json([
            'status'  => 'ok',
            'message' => 'Hasil identifikasi berhasil disimpan',
        ]);
    }
}
