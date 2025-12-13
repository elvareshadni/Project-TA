<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilIdentifikasi;

class RekapDataController extends Controller
{
    public function index()
    {
        $rekapData = HasilIdentifikasi::orderBy('created_at', 'desc')->get();

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
}
