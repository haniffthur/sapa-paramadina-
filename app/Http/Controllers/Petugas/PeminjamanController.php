<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
   public function index(Request $request)
{
    $query = \App\Models\Peminjaman::with(['user', 'details.asset.room'])->latest();

    // Filter Pencarian Text (Nama Mahasiswa / NIM / Nama Aset)
    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('user', function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('nim', 'like', "%{$search}%");
        })->orWhereHas('details.asset', function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        });
    }

    // Filter Tanggal
    if ($request->filled('date')) {
        $query->whereDate('start_time', $request->date);
    }

    $peminjamans = $query->paginate(15)->withQueryString(); // Wajib withQueryString!

    return view('petugas.peminjaman.index', compact('peminjamans'));
}
}