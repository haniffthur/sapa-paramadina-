<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\AssetReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data Widget Statistik Khusus Operasional
        $data['pending_count'] = Peminjaman::where('status', 'pending')->count();
        $data['active_count'] = Peminjaman::whereIn('status', ['approved', 'active'])->count();
        $data['late_count'] = Peminjaman::where('status', 'late')->count();
        
        $data['today_completed'] = Peminjaman::where('status', 'completed')
                                    ->whereDate('updated_at', today())
                                    ->count();

        // 2. Data Tabel Antrean (Ambil yang aktif, telat, dan pending buat dipantau)
        $data['loans'] = Peminjaman::with(['user', 'details.asset.room'])
                                    ->whereIn('status', ['pending', 'approved', 'active', 'late'])
                                    ->latest()
                                    ->get();

        return view('petugas.dashboard', $data);
    }

    // Fungsi untuk memproses laporan kerusakan dari Modal
    public function storeReport(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'deskripsi_kerusakan' => 'required|string',
            // Validasi foto (maks 2MB)
            'foto_kerusakan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $path = null;
        if ($request->hasFile('foto_kerusakan')) {
            $path = $request->file('foto_kerusakan')->store('asset_reports', 'public');
        }

        AssetReport::create([
            'asset_id' => $request->asset_id,
            'petugas_id' => Auth::id(),
            'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
            'foto_kerusakan' => $path,
            'status' => 'menunggu', // Status default nunggu Admin
        ]);

        return redirect()->back()->with('success', 'Laporan kerusakan aset berhasil dikirim ke Admin!');
    }

    public function historyReport()
{
    // Mengambil riwayat laporan yang dibuat oleh petugas yang sedang login
    $reports = AssetReport::with('asset.room')
                ->where('petugas_id', auth()->id())
                ->latest()
                ->paginate(10);

    return view('petugas.reports.index', compact('reports'));
}   
}