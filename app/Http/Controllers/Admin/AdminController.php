<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Asset;
use Illuminate\Http\Request;
use App\Events\PeminjamanApproved;
use App\Events\PeminjamanRejected;
use App\Models\Penalty;
use App\Models\Room;
use App\Models\Prodi;

class AdminController extends Controller
{
   public function index()
    {
        // 1. Data Statistik (Buat Widget Cards)
        $data['total_rooms'] = Room::count();
        $data['total_assets'] = Asset::count();
        $data['total_prodis'] = Prodi::count();
        
        // Hitung pengajuan yang butuh persetujuan (Pending)
        $data['pending_loans_count'] = Peminjaman::where('status', 'pending')->count();

        // 2. Data Tabel (Ambil 5 Pengajuan Terbaru buat di-review cepat)
        $data['recent_loans'] = Peminjaman::with(['user', 'details.asset'])
                                    ->latest()
                                    ->take(5)
                                    ->get();

        return view('admin.dashboard', $data);
    }

    // Halaman List Denda
    public function penalties()
    {
        $penalties = Penalty::with(['user', 'peminjaman.asset'])->latest()->paginate(10);
        return view('admin.penalties.index', compact('penalties'));
    }

    // Mark denda sebagai lunas
    public function markAsPaid($id)
    {
        $penalty = Penalty::findOrFail($id);
        $penalty->update(['status' => 'paid']);
        
        return back()->with('success', 'Denda berhasil ditandai sebagai lunas!');
    }

   public function report(Request $request)
{
    // UPDATE: Ganti 'asset' menjadi 'details.asset'
    $query = Peminjaman::with(['user', 'details.asset']);

    // Fitur Filter Berdasarkan Status
    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }

    // Fitur Filter Tanggal
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
    }

    $history = $query->latest()->paginate(15);

    return view('admin.reports.index', compact('history'));
}

}