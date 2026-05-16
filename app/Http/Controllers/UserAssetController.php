<?php

namespace App\Http\Controllers;

use App\Models\Categories; 
use App\Models\Room;
use App\Models\Asset;
use App\Models\Peminjaman;
use App\Models\Penalty;
use App\Models\Prodi; 
use App\Models\Setting; // <-- INI WAJIB DITAMBAH BIAR BISA CEK HARGA DENDA
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\PeminjamanDetail;

class UserAssetController extends Controller
{
    /**
     * Dashboard Mahasiswa
     * Menampilkan pinjaman aktif, denda, dan jadwal kalender
     */
    public function index()
    {
        $user = Auth::user();
        
        // 1. Data Pinjaman Aktif Mahasiswa
        $data['active_loans'] = Peminjaman::where('user_id', $user->id)
                                ->whereIn('status', ['active', 'approved', 'late'])
                                ->with(['details.asset.room', 'details.asset.category'])
                                ->latest()
                                ->get();

        // 2. Data Tunggakan Denda
        $data['unpaid_penalty'] = Penalty::where('user_id', $user->id)
                                 ->where('status', 'unpaid')
                                 ->sum('amount');

        // 3. Data Jadwal Booking untuk Kalender (FullCalendar)
        $bookings = Peminjaman::whereIn('status', ['pending', 'approved', 'active'])
                              ->with(['details.asset.room', 'user'])
                              ->get();
        
        $events = [];
        foreach ($bookings as $booking) {
            $roomName = $booking->details->first()?->asset?->room?->name ?? 'Ruangan Lab';
            $events[] = [
                'title' => $roomName . ' (' . $booking->user->name . ')',
                'start' => Carbon::parse($booking->start_time)->toIso8601String(),
                'end'   => Carbon::parse($booking->end_time)->toIso8601String(),
                'color' => $booking->status == 'pending' ? '#f59e0b' : '#3b82f6',
            ];
        }
        $data['calendar_events'] = json_encode($events);

        return view('dashboard', $data);
    }

    /**
     * Katalog Asset
     */
    public function showAssets()
    {
        $assets = Asset::with(['category', 'room'])->where('quantity', '>', 0)->get();
        $categories = Categories::all(); 
        return view('user.assets_list', compact('assets', 'categories'));
    }

    /**
     * Halaman Kamera Scan QR
     */
    public function scanArea()
    {
        return view('user.scan');
    }

    /**
     * Hasil Scan QR Pintu Ruangan
     */
    public function scanRoom($token)
    {
        $room = Room::where('qr_code_token', $token)->with('assets.category')->firstOrFail();
        return view('user.room_assets', compact('room'));
    }

    /**
     * Konfirmasi Pinjam (Single Asset dari Scan)
     */
    public function confirmPinjam($asset_id)
    {
        $asset = Asset::with(['category', 'room'])->findOrFail($asset_id);
        return view('user.confirm_pinjam', compact('asset'));
    }

    /**
     * Simpan Peminjaman (Single Asset)
     */
    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'qty_pinjam' => 'required|integer|min:1',
            'duration' => 'required|numeric|min:0.5',
            'reason' => 'required|string|min:5',
        ]);

        $asset = Asset::findOrFail($request->asset_id);
        
        // Cek stok
        if ($asset->quantity < $request->qty_pinjam) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        $startTime = Carbon::now();
        $endTime = $startTime->copy()->addHours($request->duration);

        $peminjaman = Peminjaman::create([
            'user_id' => Auth::id(),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'pending',
            'reason' => $request->reason,
        ]);

        PeminjamanDetail::create([
            'peminjaman_id' => $peminjaman->id,
            'asset_id' => $request->asset_id,
            'quantity' => $request->qty_pinjam
        ]);

        // Potong stok langsung karena pinjam sekarang
        $asset->decrement('quantity', $request->qty_pinjam);

        // Notif ke Admin
        try {
            event(new \App\Events\PeminjamanBaru("Peminjaman baru dari " . Auth::user()->name));
        } catch (\Exception $e) {}

        return redirect()->route('dashboard')->with('success', 'Peminjaman berhasil diajukan!');
    }

    /**
     * Proses Simpan Peminjaman Multi-Item (Dari Keranjang/Scan Room)
     */
    public function storeMultiPeminjaman(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'items' => 'required|array', 
            'reason' => 'required|string|min:5',
            'duration' => 'required|numeric|min:1|max:24',
            'booking_start' => 'nullable|date',
        ]);

        $duration = $request->integer('duration');
        $startTime = $request->filled('booking_start') ? Carbon::parse($request->booking_start) : Carbon::now();
        $endTime = $startTime->copy()->addHours($duration);

        // --- 1. CEK ANTI-BENTROK ---
        foreach ($request->items as $asset_id => $qty) {
            if ($qty > 0) {
                $isBentrok = PeminjamanDetail::where('asset_id', $asset_id)
                    ->whereHas('peminjaman', function ($q) use ($startTime, $endTime) {
                        $q->whereIn('status', ['pending', 'approved', 'active'])
                          ->where('start_time', '<', $endTime)
                          ->where('end_time', '>', $startTime);
                    })->exists();

                if ($isBentrok) {
                    $assetName = Asset::find($asset_id)->name;
                    return back()->with('error', "Gagal! '$assetName' sudah dibooking orang lain.");
                }
            }
        }

        $hasItems = false;
        $peminjaman = Peminjaman::create([
            'user_id' => Auth::id(),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'pending',
            'reason' => $request->reason,
        ]);

        foreach ($request->items as $asset_id => $qty) {
            if ($qty > 0) {
                $asset = Asset::findOrFail($asset_id);
                if ($asset->quantity >= $qty) {
                    $hasItems = true;
                    if ($startTime->isToday()) {
                        $asset->decrement('quantity', $qty);
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
            return back()->with('error', 'Gagal! Stok tidak mencukupi.');
        }

        // --- PUSHER: Notif Ke Admin ---
        try {
            event(new \App\Events\PeminjamanBaru("Peminjaman baru dari " . Auth::user()->name));
        } catch (\Exception $e) {}

        return redirect()->route('dashboard')->with('success', 'Berhasil diajukan!');
    }

    /**
     * Proses Pengembalian (Selesai) + LOGIKA DENDA OTOMATIS
     */
    public function selesai($id)
    {
        $loan = Peminjaman::with('details.asset')->findOrFail($id);
        $now = Carbon::now();
        $endTime = Carbon::parse($loan->end_time);

        // --- 1. LOGIKA CEK KETERLAMBATAN & DENDA OTOMATIS ---
        if ($now->greaterThan($endTime)) {
            // Hitung selisih jam (telat menit pun hitung 1 jam)
            $hoursLate = $now->diffInHours($endTime);
            if ($hoursLate == 0) $hoursLate = 1; 

            // Ambil tarif denda per jam dari tabel Setting (Default 5000)
            $penaltyRate = Setting::where('key', 'penalty_per_hour')->value('value') ?? 5000;
            $totalPenalty = $hoursLate * $penaltyRate;

            // Masukkan ke tabel Penalty
            Penalty::create([
                'user_id' => $loan->user_id,
                'peminjaman_id' => $loan->id,
                'amount' => $totalPenalty,
                'description' => "Terlambat mengembalikan selama {$hoursLate} jam.",
                'status' => 'unpaid'
            ]);
            
            $msg = "Aset dikembalikan, tapi kamu TELAT! Denda otomatis Rp " . number_format($totalPenalty, 0, ',', '.') . " masuk ke tagihan kamu.";
        } else {
            $msg = "Aset berhasil dikembalikan tepat waktu. Mantap!";
        }

        // --- 2. KEMBALIKAN STOK ASET ---
        foreach($loan->details as $detail) {
            $detail->asset->increment('quantity', $detail->quantity);
            $detail->asset->update(['status' => 'available']);
        }

        // --- 3. UPDATE STATUS PEMINJAMAN ---
        $loan->update([
            'status' => 'completed', 
            'actual_return_time' => $now 
        ]);

        // --- 4. NOTIF PUSHER (OPSIONAL) ---
        try {
            event(new \App\Events\PeminjamanCompleted($loan)); 
        } catch (\Exception $e) {}
        
        return back()->with('success', $msg);
    }

    /**
     * Riwayat Peminjaman User
     */
    public function history()
    {
        $history = Peminjaman::where('user_id', Auth::id())
                    ->with(['details.asset.room', 'details.asset.category']) 
                    ->latest()
                    ->get();
        return view('user.history', compact('history'));
    }

    /**
     * Daftar Denda User
     */
    public function penalties() {
        $penalties = Penalty::where('user_id', Auth::id())->latest()->get();
        return view('user.penalties', compact('penalties'));
    }

    /**
     * Halaman Profil & Update Data (NIM/Prodi)
     */
    public function profile() {
        $user = Auth::user();
        $prodis = Prodi::all(); // Untuk dropdown di view
        return view('user.profile', compact('user', 'prodis'));
    }

    /**
     * Update Data NIM dan Prodi (Dibutuhkan oleh Middleware EnsureProfileIsComplete)
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'nim' => 'required|numeric|unique:users,nim,' . Auth::id(),
            'prodi_id' => 'required|exists:prodis,id',
        ]);

        Auth::user()->update([
            'nim' => $request->nim,
            'prodi_id' => $request->prodi_id,
        ]);

        return redirect()->route('dashboard')->with('success', 'Data profil berhasil diperbarui!');
    }

    /**
     * Detail Aset
     */
    public function assetDetail($id) {
        $asset = Asset::with(['category', 'room'])->findOrFail($id);
        return view('user.asset_detail', compact('asset'));
    }

    /**
     * Proses Upload Bukti Bayar Denda
     */
    public function payPenalty(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Validasi file gambar max 2MB
        ]);

        // Pastikan denda ini memang milik mahasiswa yang sedang login
        $penalty = Penalty::where('user_id', Auth::id())->findOrFail($id);

        // Proses simpan file bukti pembayaran
        if ($request->hasFile('payment_proof')) {
            // File akan disimpan di folder storage/app/public/payment_proofs
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            
            // Update status denda jadi 'pending' supaya admin bisa verifikasi
            $penalty->update([
                'payment_proof' => $path, // Pastikan kolom 'payment_proof' sudah ada di tabel penalties
                'status' => 'pending', 
                'description' => $penalty->description . ' (Menunggu Verifikasi Admin)'
            ]);
        }

        return back()->with('success', 'Mantap! Bukti pembayaran berhasil diupload. Tunggu Admin verifikasi ya!');
    }
}