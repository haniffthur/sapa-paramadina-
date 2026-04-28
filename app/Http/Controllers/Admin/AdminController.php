<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Asset;
use Illuminate\Http\Request;
use App\Events\PeminjamanApproved;
use App\Events\PeminjamanRejected;
use App\Models\Penalty;

class AdminController extends Controller
{
   public function index()
    {
        $data['pending_count'] = Peminjaman::where('status', 'pending')->count();
        $data['active_loan'] = Peminjaman::whereIn('status', ['active', 'late'])->count();
        $data['total_assets'] = Asset::count();
        
        // Statistik Denda
        $data['unpaid_penalties'] = Penalty::where('status', 'unpaid')->count();
        $data['total_fine_amount'] = Penalty::where('status', 'unpaid')->sum('amount');

        $data['recent_requests'] = Peminjaman::with(['user', 'asset'])
                                    ->where('status', 'pending')
                                    ->latest()->take(5)->get();

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
    // Eager loading user dan asset biar gak lambat
    $query = Peminjaman::with(['user', 'asset']);

    // Fitur Filter Berdasarkan Status (Pending, Approved, Rejected, Completed)
    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }

    // Fitur Filter Tanggal (Jika admin mau liat rekap bulan ini saja)
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
    }

    $history = $query->latest()->paginate(15);

    return view('admin.reports.index', compact('history'));
}

}