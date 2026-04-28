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

class UserAssetController extends Controller
{
    // Dashboard Mahasiswa
    public function index()
    {
        $user = Auth::user();
        
        // Mengambil pinjaman yang sedang berjalan
        $data['active_loans'] = Peminjaman::where('user_id', $user->id)
                                ->whereIn('status', ['active', 'late', 'pending'])
                                ->with(['asset.room', 'asset.category'])
                                ->latest()
                                ->get();

        // Menghitung total denda yang belum dibayar
        $data['unpaid_penalty'] = Penalty::where('user_id', $user->id)
                                 ->where('status', 'unpaid')
                                 ->sum('amount');

        return view('dashboard', $data);
    }

    // Katalog Asset (Tampilan Grid 2 Kolom)
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

    // Hasil Scan QR Pintu
    public function scanRoom($token)
    {
        $room = Room::where('qr_code_token', $token)->with('assets')->firstOrFail();
        return view('user.room_assets', compact('room'));
    }

    // Form Konfirmasi Pinjam
    public function confirmPinjam($asset_id)
    {
        $asset = Asset::with(['room', 'category'])->findOrFail($asset_id);
        
        if ($asset->quantity <= 0) {
            return back()->with('error', 'Stok aset ini sudah habis.');
        }

        return view('user.confirm_pinjam', compact('asset'));
    }

    // Proses Simpan Peminjaman
    public function storePeminjaman(Request $request)
    {
        $asset = Asset::findOrFail($request->asset_id);

        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'qty_pinjam' => "required|numeric|min:1|max:{$asset->quantity}",
            'reason' => 'required|string|min:5',
            'duration' => 'required|numeric|min:0.5|max:24',
        ]);

        // 1. Kurangi stok di tabel assets
        $asset->decrement('quantity', $request->qty_pinjam);

        // 2. Jika stok habis, set status jadi unavailable
        if ($asset->quantity <= 0) {
            $asset->update(['status' => 'unavailable']);
        }

        // 3. Simpan data peminjaman
        Peminjaman::create([
            'user_id' => Auth::id(),
            'asset_id' => $request->asset_id,
            'quantity' => $request->qty_pinjam,
            'start_time' => Carbon::now(),
            'end_time' => Carbon::now()->addMinutes($request->duration * 60),
            'status' => 'pending', // Menunggu approval admin atau langsung active tergantung sistem lo
            'reason' => $request->reason
        ]);

        return redirect()->route('dashboard')->with('success', 'Permintaan terkirim & stok telah dipesan!');
    }

    // Proses Pengembalian (Selesai)
    public function selesai($id)
    {
        $loan = Peminjaman::with('asset')->findOrFail($id);
        
        // 1. Tambahkan stok kembali ke tabel assets
        $loan->asset->increment('quantity', $loan->quantity);

        // 2. Set status aset kembali jadi available
        $loan->asset->update(['status' => 'available']);

        // 3. Update status peminjaman
        $loan->update([
            'status' => 'returned', 
            'returned_at' => Carbon::now()
        ]);
        
        return back()->with('success', 'Aset berhasil dikembalikan!');
    }

    // Menampilkan Riwayat Peminjaman Mahasiswa
    public function history()
    {
        $history = Peminjaman::where('user_id', Auth::id())
                    ->with(['asset.room'])
                    ->latest()
                    ->get();
        return view('user.history', compact('history'));
    }

    // Menampilkan Daftar Denda Mahasiswa
    public function penalties()
    {
        $penalties = Penalty::where('user_id', Auth::id())
                    ->latest()
                    ->get();
        return view('user.penalties', compact('penalties'));
    }

    // Halaman Profile Mahasiswa
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function storeMultiPeminjaman(Request $request)
{
    $request->validate([
        'room_id' => 'required|exists:rooms,id',
        'items' => 'required|array', // Array dari input barang tadi
        'reason' => 'required|string|min:5',
        'duration' => 'required|numeric|min:1|max:24',
    ]);

    $hasItems = false;

    // Looping semua barang yang diinput
    foreach ($request->items as $asset_id => $qty) {
        if ($qty > 0) {
            $hasItems = true;
            $asset = Asset::findOrFail($asset_id);
            
            // Keamanan tambahan: Cek stok lagi takutnya di-bypass
            if ($asset->quantity >= $qty) {
                
                // 1. Kurangi Stok
                $asset->decrement('quantity', $qty);
                if ($asset->quantity <= 0) {
                    $asset->update(['status' => 'unavailable']);
                }

                // 2. Buat Peminjaman
                Peminjaman::create([
                    'user_id' => Auth::id(),
                    'asset_id' => $asset_id,
                    'quantity' => $qty,
                    'start_time' => Carbon::now(),
                    'end_time' => Carbon::now()->addMinutes($request->duration * 60),
                    'status' => 'pending',
                    'reason' => $request->reason
                ]);
            }
        }
    }

    if (!$hasItems) {
        return back()->with('error', 'Pilih minimal 1 barang untuk dipinjam!');
    }

    return redirect()->route('dashboard')->with('success', 'Semua barang berhasil dipesan!');
}
}