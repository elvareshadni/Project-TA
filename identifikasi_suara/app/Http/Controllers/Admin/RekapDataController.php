<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilIdentifikasi;
use Illuminate\Http\Request;

class RekapDataController extends Controller
{
    public function index()
    {
        $perPage = request('per_page', 10);
        $search = request('search');

        $query = HasilIdentifikasi::orderBy('created_at', 'desc');

        if ($search) {
            $query->where('nama', 'like', "%{$search}%");
        }

        $rekapData = $query->paginate($perPage)->appends(request()->query());

        return view('admin.rekap-data', compact('rekapData'));
    }

    public function printPdf($id)
    {
        $data = HasilIdentifikasi::findOrFail($id);
        return view('admin.print-pdf', compact('data'));
    }

    public function destroy($id)
    {
        $data = HasilIdentifikasi::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    public function exportCsv(Request $request)
    {
        // Ambil data yang sama dengan tabel
        $data = HasilIdentifikasi::orderBy('created_at', 'desc')->get();

        // Tentukan mode: preview / download
        $mode = $request->get('mode', 'preview');
        $filename = 'rekap_identifikasi_suara.csv';

        $headers = [
            "Content-Type"        => "text/csv",
            "Content-Disposition" => ($mode === 'download'
                ? "attachment"
                : "inline") . "; filename={$filename}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate",
            "Expires"             => "0",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'Nama', 'Gender', 'Usia', 'Durasi',
                'Happy', 'Sad', 'Angry', 'Surprised', 'Neutral',
                'Jawa', 'Sunda', 'Batak', 'Minang', 'Betawi',
                'Waktu'
            ]);

            foreach ($data as $row) {
                $em = $row->distribution_by_emotion ?? [];
                $sk = $row->distribution_by_suku ?? [];

                fputcsv($file, [
                    $row->nama ?? '-',
                    $row->gender ?? '-',
                    $row->usia ?? '-',
                    $row->durasi ?? '-',
                    $em['Happy']['percent'] ?? 0,
                    $em['Sad']['percent'] ?? 0,
                    $em['Angry']['percent'] ?? 0,
                    $em['Surprised']['percent'] ?? 0,
                    $em['Neutral']['percent'] ?? 0,
                    $sk['Jawa']['percent'] ?? 0,
                    $sk['Sunda']['percent'] ?? 0,
                    $sk['Batak']['percent'] ?? 0,
                    $sk['Minang']['percent'] ?? 0,
                    $sk['Betawi']['percent'] ?? 0,
                    optional($row->created_at)->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
