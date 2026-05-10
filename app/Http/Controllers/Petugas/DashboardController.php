<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\AssetReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik untuk widget dashboard petugas
        $data['pending_count'] = Peminjaman::where('status', 'pending')->count();
        $data['active_count'] = Peminjaman::whereIn('status', ['approved', 'active'])->count();
        $data['late_count'] = Peminjaman::where('status', 'late')->count();
        
        $data['today_completed'] = Peminjaman::where('status', 'completed')
                                    ->whereDate('updated_at', today())
                                    ->count();

        // Data antrean peminjaman untuk dipantau OB
        $data['loans'] = Peminjaman::with(['user', 'details.asset.room'])
                                    ->whereIn('status', ['pending', 'approved', 'active', 'late'])
                                    ->latest()
                                    ->get();

        return view('petugas.dashboard', $data);
    }

    public function storeReport(Request $request)
    {
        // Validasi input
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjamans,id',
            'asset_id' => 'required|exists:assets,id',
            'deskripsi_kerusakan' => 'required|string',
            'foto_kerusakan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nominal_denda' => 'nullable|numeric|min:0'
        ]);

        // Upload Foto
        $fotoPath = null;
        if ($request->hasFile('foto_kerusakan')) {
            $fotoPath = $request->file('foto_kerusakan')->store('laporan_kerusakan', 'public');
        }

        // Simpan Laporan Kerusakan
        AssetReport::create([
            'peminjaman_id' => $request->peminjaman_id, // KRUSIAL: Biar Admin tau siapa pelakunya
            'asset_id' => $request->asset_id,
            'petugas_id' => Auth::id(),
            'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
            'foto_kerusakan' => $fotoPath,
            'nominal_denda' => $request->nominal_denda,
            'status' => 'menunggu', // Status awal agar muncul di Admin
        ]);

        return redirect()->back()->with('success', 'Laporan kerusakan & usulan denda berhasil dikirim ke Admin!');
    }

    public function historyReport(Request $request)
    {
        $query = \App\Models\AssetReport::with(['asset.room', 'peminjaman.user'])->latest();

        // Filter Pencarian (Nama Aset / Ruangan)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('asset', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('room', function($r) use ($search) {
                      $r->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Tanggal Pelaporan
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $reports = $query->paginate(15)->withQueryString();

        return view('petugas.reports.index', compact('reports'));
    }
}