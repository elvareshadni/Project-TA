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
            'durasi'    => 'nullable|string',
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
            'durasi'     => $data['durasi'] ?? null,
            'hasil'      => $data['hasil'],
            'akurasi'    => $data['akurasi'] ?? null,

            'distribution_by_emotion' => $data['distribution_by_emotion'] ?? null,
            'distribution_by_suku'    => $data['distribution_by_suku'] ?? null,
        ]);

        if (!empty($data['email'])) {
            try {
                \Illuminate\Support\Facades\Mail::to($data['email'])
                    ->send(new \App\Mail\HasilAnalisisMail($data));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Gagal kirim email: ' . $e->getMessage());
            }
        }

        return response()->json([
            'status'  => 'ok',
            'message' => 'Hasil identifikasi berhasil disimpan & email dikirim',
            'sent_to' => $data['email'] ?? null
        ]);
    }

    public function preview(Request $request)
    {
        // Validasi data agar sesuai struktur view
        $data = $request->validate([
            'sumber'    => 'nullable|string',
            'file_suara'=> 'nullable|string',
            'durasi'    => 'nullable|string',
            'hasil'     => 'required|string',
            'akurasi'   => 'nullable', // numeric or string depending on format

            'distribution_by_emotion' => 'nullable',
            'distribution_by_suku'    => 'nullable',

            'nama'   => 'nullable|string',
            'email'  => 'nullable|email',
            'gender' => 'nullable|string',
            'usia'   => 'nullable',
        ]);

        // Decode JSON string to array for view
        if (is_string($data['distribution_by_emotion'] ?? null)) {
            $data['distribution_by_emotion'] = json_decode($data['distribution_by_emotion'], true);
        }
        if (is_string($data['distribution_by_suku'] ?? null)) {
            $data['distribution_by_suku'] = json_decode($data['distribution_by_suku'], true);
        }

        // Langsung tampilkan view (reuse template email)
        return view('emails.hasil_analisis', ['data' => $data]);
    }
}
