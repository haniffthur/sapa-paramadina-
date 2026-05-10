<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Events\PeminjamanApproved;
use App\Events\PeminjamanRejected;
use App\Events\PeminjamanCompleted;
use App\Models\Penalty;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /**
     * Menampilkan semua daftar pengajuan peminjaman (Approval Queue)
     */
    public function index()
    {
        // Mengambil data peminjaman dengan relasi user dan detail aset (Eager Loading)
        $requests = Peminjaman::with(['user', 'details.asset.room'])
                    ->latest()
                    ->paginate(10);
                    
        return view('admin.peminjaman.index', compact('requests'));
    }

    /**
     * Approve Peminjaman
     * Mengubah status menjadi 'active' dan mengirim notif ke mahasiswa
     */
    public function approve($id) 
    {
        $peminjaman = Peminjaman::with(['details.asset', 'user'])->findOrFail($id);
        
        // Update status ke active agar mahasiswa bisa menggunakan aset
        $peminjaman->update(['status' => 'active']);

        // Trigger Pusher Notif Approve
        try {
            event(new PeminjamanApproved($peminjaman));
        } catch (\Exception $e) {
            \Log::error("Pusher Approve Error: " . $e->getMessage());
        }

        return back()->with('success', 'Peminjaman ' . $peminjaman->user->name . ' telah disetujui!');
    }

    /**
     * Reject Peminjaman
     * Memberikan catatan alasan penolakan dan mengembalikan stok jika sudah terpotong
     */
   public function reject(Request $request, $id)
{
    try {
        // 1. Load relasi details.asset (WAJIB biar gak Error 500)
        $peminjaman = Peminjaman::with(['details.asset', 'user'])->findOrFail($id);
        
        // 2. KEMBALIKAN STOK (Karena sistem lo sekarang auto-potong pas mahasiswa klik pinjam)
        // Kita looping semua barang yang ada di pengajuan ini
        foreach ($peminjaman->details as $detail) {
            if ($detail->asset) {
                $detail->asset->increment('quantity', $detail->quantity);
                
                // Jika stok balik jadi > 0, pastikan statusnya available lagi
                if ($detail->asset->quantity > 0) {
                    $detail->asset->update(['status' => 'available']);
                }
            }
        }

        // 3. Update status ke rejected
        $peminjaman->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note ?? 'Maaf, pengajuan ditolak karena alasan operasional/jadwal bentrok.'
        ]);

        // 4. Trigger Pusher Notif Reject ke Mahasiswa
        try {
            event(new \App\Events\PeminjamanRejected($peminjaman));
        } catch (\Exception $e) {
            \Log::error("Pusher Reject Error: " . $e->getMessage());
        }

        return back()->with('error', 'Peminjaman berhasil ditolak dan stok dikembalikan.');

    } catch (\Exception $e) {
        // Cek log di storage/logs/laravel.log kalau masih error
        \Log::error("Error Reject: " . $e->getMessage());
        return back()->with('error', 'Gagal reject: ' . $e->getMessage());
    }
}

    /**
     * Complete Peminjaman (Selesai/Kembalikan)
     * Digunakan saat barang dikembalikan oleh mahasiswa
     */
    public function complete($id)
    {
        $peminjaman = Peminjaman::with(['details.asset', 'user'])->findOrFail($id);

        // 1. Update status dan catat waktu asli pengembalian
        $peminjaman->update([
            'status' => 'completed',
            'actual_return_time' => Carbon::now()
        ]);

        // 2. Kembalikan stok fisik aset ke database
        foreach ($peminjaman->details as $detail) {
            $detail->asset->increment('quantity', $detail->quantity);
            
            // Jika sebelumnya aset berstatus 'unavailable', balikkan ke 'available'
            if ($detail->asset->quantity > 0) {
                $detail->asset->update(['status' => 'available']);
            }
        }

        // 3. Trigger Pusher Notif Selesai ke Mahasiswa
        try {
            event(new PeminjamanCompleted($peminjaman));
        } catch (\Exception $e) {
            \Log::error("Pusher Complete Error: " . $e->getMessage());
        }

        return back()->with('success', 'Aset telah dikembalikan, stok berhasil diperbarui!');
    }

    
}