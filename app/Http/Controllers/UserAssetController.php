<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Room;
use App\Models\Asset;
use App\Models\Peminjaman;
use App\Models\Penalty;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\PeminjamanDetail;

class UserAssetController extends Controller
{
    // Dashboard Mahasiswa
    public function index()
    {
        $user = Auth::user();
        
        // 1. Data Pinjaman Aktif Mahasiswa
        $data['active_loans'] = Peminjaman::where('user_id', $user->id)
                                ->whereIn('status', ['active', 'approved', 'late'])
                                ->with(['details.asset.room', 'details.asset.category'])
                                ->latest()
                                ->get();

        // 2. Data Tunggakan
        $data['unpaid_penalty'] = Penalty::where('user_id', $user->id)
                                 ->where('status', 'unpaid')
                                 ->sum('amount');

        // 3. BARU: Data Semua Jadwal Booking (Untuk Kalender)
        $bookings = Peminjaman::whereIn('status', ['pending', 'approved', 'active'])
                              ->with(['details.asset.room', 'user'])
                              ->get();
        
        // Format data biar cocok sama FullCalendar.js
        $events = [];
        foreach ($bookings as $booking) {
            $roomName = $booking->details->first()?->asset?->room?->name ?? 'Ruangan Lab';
            $events[] = [
                'title' => $roomName . ' (' . $booking->user->name . ')',
                'start' => Carbon::parse($booking->start_time)->toIso8601String(),
                'end'   => Carbon::parse($booking->end_time)->toIso8601String(),
                'color' => $booking->status == 'pending' ? '#f59e0b' : '#3b82f6', // Orange untuk pending, Biru untuk approved
            ];
        }
        $data['calendar_events'] = json_encode($events);

        return view('dashboard', $data);
    }

    // Katalog Asset
    public function showAssets()
    {
        $assets = Asset::with(['category', 'room'])->where('quantity', '>', 0)->get();
        $categories = Categories::all();
        return view('user.assets_list', compact('assets', 'categories'));
    }

    // Halaman Kamera Scan
    public function scanArea()
    {
        return view('user.scan');
    }

    // Hasil Scan QR Pintu & Form Booking
    public function scanRoom($token)
    {
        $room = Room::where('qr_code_token', $token)->with('assets.category')->firstOrFail();
        return view('user.room_assets', compact('room'));
    }

    // Proses Simpan Peminjaman & Booking Anti-Bentrok
    public function storeMultiPeminjaman(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'items' => 'required|array', 
            'reason' => 'required|string|min:5',
            'duration' => 'required|numeric|min:1|max:24',
            'booking_start' => 'nullable|date', // Tambahan Validasi Booking Waktu
        ]);

        // AMAN 1: Paksa ubah input durasi dari HTML (String) jadi Angka Bulat (Integer)
        $duration = $request->integer('duration');

        // AMAN 2: Cek apakah input tanggal benar-benar diisi oleh mahasiswa
        if ($request->filled('booking_start')) {
            // Kalau "Booking Nanti" dipilih dan tanggal diisi
            $startTime = Carbon::parse($request->booking_start);
        } else {
            // Kalau "Pinjam Sekarang" dipilih
            $startTime = Carbon::now();
        }

        // Penambahan jam sekarang dijamin 100% aman karena $duration sudah jadi integer
        $endTime = $startTime->copy()->addHours($duration);

        // --- 1. CEK ANTI-BENTROK DULU ---
        foreach ($request->items as $asset_id => $qty) {
            if ($qty > 0) {
                // Logika: Cari apakah aset ini sedang dipakai/dibooking di rentang waktu yang diminta
                $isBentrok = PeminjamanDetail::where('asset_id', $asset_id)
                    ->whereHas('peminjaman', function ($q) use ($startTime, $endTime) {
                        $q->whereIn('status', ['pending', 'approved', 'active'])
                          // Rumus sakti cek Overlap Waktu: StartA < EndB AND EndA > StartB
                          ->where('start_time', '<', $endTime)
                          ->where('end_time', '>', $startTime);
                    })->exists();

                if ($isBentrok) {
                    $assetName = Asset::find($asset_id)->name;
                    return back()->with('error', "Gagal! '$assetName' sudah di-booking orang lain pada rentang waktu tersebut.");
                }
            }
        }

        $hasItems = false;

        // --- 2. BUAT DATA MASTER ---
        $peminjaman = Peminjaman::create([
            'user_id' => Auth::id(),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'pending',
            'reason' => $request->reason,
        ]);

        // --- 3. MASUKKAN KE KERANJANG DETAIL ---
        foreach ($request->items as $asset_id => $qty) {
            if ($qty > 0) {
                $asset = Asset::findOrFail($asset_id);
                
                if ($asset->quantity >= $qty) {
                    $hasItems = true;
                    
                    // Kalau start time-nya hari ini/sekarang, langsung potong stok fisik
                    if ($startTime->isToday()) {
                        $asset->decrement('quantity', $qty);
                        if ($asset->quantity <= 0) {
                            $asset->update(['status' => 'unavailable']);
                        }
                    }

                    PeminjamanDetail::create([
                        'peminjaman_id' => $peminjaman->id,
                        'asset_id' => $asset_id,
                        'quantity' => $qty
                    ]);
                }
            }
        }

        if (!$hasItems) {
            $peminjaman->delete();
            return back()->with('error', 'Gagal meminjam. Stok tidak mencukupi atau tidak ada barang yang dipilih!');
        }

        return redirect()->route('dashboard')->with('success', 'Berhasil diajukan! (Menunggu approve dari admin)');
    }

    // Proses Pengembalian (Selesai)
    public function selesai($id)
    {
        $loan = Peminjaman::with('details.asset')->findOrFail($id);
        
        foreach($loan->details as $detail) {
            $detail->asset->increment('quantity', $detail->quantity);
            $detail->asset->update(['status' => 'available']);
        }

        $loan->update([
            'status' => 'completed', 
            'actual_return_time' => Carbon::now() 
        ]);
        
        return back()->with('success', 'Semua aset berhasil dikembalikan!');
    }

    // Riwayat, Denda, Profil dll...
    public function history()
    {
        $history = Peminjaman::where('user_id', Auth::id())
                    // TAMBAHKAN category DI SINI BIAR DATANYA KETARIK
                    ->with(['details.asset.room', 'details.asset.category']) 
                    ->latest()
                    ->get();
                    
        return view('user.history', compact('history'));
    }

    public function penalties() {
        $penalties = Penalty::where('user_id', Auth::id())->latest()->get();
        return view('user.penalties', compact('penalties'));
    }

    public function profile() {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function assetDetail($id) {
        $asset = Asset::with(['category', 'room'])->findOrFail($id);
        return view('user.asset_detail', compact('asset'));
    }
}