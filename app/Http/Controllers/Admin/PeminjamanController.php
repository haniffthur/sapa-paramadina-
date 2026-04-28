<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Events\PeminjamanApproved;
use App\Events\PeminjamanRejected;
use App\Models\Penalty;

class PeminjamanController extends Controller
{
    public function index()
    {
        $requests = Peminjaman::with(['user', 'asset.room'])->latest()->paginate(10);
        return view('admin.peminjaman.index', compact('requests'));
    }

    public function approve($id) {
    $peminjaman = Peminjaman::with('asset', 'user')->findOrFail($id);
    $peminjaman->update(['status' => 'active']);
    
    // Kurangi stok aset saat diapprove (kalau mau real-time di sini)
    // $peminjaman->asset->decrement('quantity', $peminjaman->quantity);

    // Trigger Pusher!
    event(new PeminjamanApproved($peminjaman));

    return back()->with('success', 'Pinjaman berhasil diapprove!');
}

    public function reject(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('asset', 'user')->findOrFail($id);
        $peminjaman->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note ?? 'Ditolak oleh admin.'
        ]);
        return back()->with('error', 'Peminjaman ditolak.');
    }
}