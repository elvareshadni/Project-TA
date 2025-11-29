<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class RekapDataController extends Controller
{
    public function index()
    {
        $rekapData = User::with('hasil')->orderBy('created_at', 'desc')->get();

        return view('admin.rekap-data', compact('rekapData'));
    }
}

