<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssetReport;
use App\Models\Penalty;
use Illuminate\Http\Request;

class AssetReportController extends Controller
{
    public function index()
    {
        // Mengambil laporan dengan relasi agar tidak 'User Dihapus'
        // Diurutkan: Menunggu di atas, Selesai di bawah
        $reports = AssetReport::with(['petugas', 'asset', 'peminjaman.user'])
            ->orderByRaw("FIELD(status, 'menunggu', 'selesai') ASC")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.reportasset.index', compact('reports'));
    }

    public function approveAndPenalty(Request $request, $id)
    {
        $report = AssetReport::with('peminjaman.user')->findOrFail($id);

        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        // Safety Check: Pastikan data peminjaman ada
        if (!$report->peminjaman) {
            return back()->with('error', 'Gagal! Data peminjaman asli tidak ditemukan.');
        }

        $userId = $report->peminjaman->user_id;

        // 1. Jika Admin set denda > 0, buat record di tabel Penalty
        if ($request->amount > 0) {
            Penalty::create([
                'user_id' => $userId,
                'peminjaman_id' => $report->peminjaman_id,
                'amount' => $request->amount,
                'description' => 'Denda Kerusakan (Laporan Petugas): ' . $report->deskripsi_kerusakan,
                'status' => 'unpaid'
            ]);
        }

        // 2. Update status laporan menjadi selesai
        $report->update(['status' => 'selesai']);

        return back()->with('success', 'Laporan berhasil dieksekusi dan denda telah dicatat.');
        
    }
}