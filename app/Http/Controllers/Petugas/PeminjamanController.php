<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        // Menampilkan semua peminjaman kecuali yang sudah selesai/ditolak
        $peminjamans = Peminjaman::with(['user', 'details.asset.room'])
            ->whereIn('status', ['approved', 'active', 'late'])
            ->latest()
            ->paginate(10);

        return view('petugas.peminjaman.index', compact('peminjamans'));
    }
}