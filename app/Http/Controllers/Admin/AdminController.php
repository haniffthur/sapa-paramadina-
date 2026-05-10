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
use App\Models\User;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
   public function index()
{
    $data['total_rooms'] = Room::count();
    $data['total_assets'] = Asset::count();
    $data['total_prodis'] = Prodi::count();
    
    // Hitung pengajuan yang statusnya beneran 'pending'
    $data['pending_loans_count'] = Peminjaman::where('status', 'pending')->count();

    // Data tabel untuk aktivitas terbaru
    $data['recent_loans'] = Peminjaman::with(['user', 'details.asset'])
                                ->latest()
                                ->take(5)
                                ->get();

    return view('admin.dashboard', $data);
}

    // Halaman List Denda
    public function penalties() 
    {
        $penalties = Penalty::with('user')->latest()->get();
        // Ambil semua data mahasiswa untuk di dropdown
        $mahasiswas = User::where('role', 'mahasiswa')->get(); 
        
        return view('admin.penalties.index', compact('penalties', 'mahasiswas'));
    }

    // 2. Tambahkan fungsi baru ini di bawahnya:
    public function storeManualPenalty(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1000',
            'description' => 'required|string|min:5'
        ]);

        Penalty::create([
            'user_id' => $request->user_id,
            'peminjaman_id' => null, // Karena manual, gak terikat ke 1 ID pinjaman
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => 'unpaid'
        ]);

        return back()->with('success', 'Denda manual berhasil ditambahkan!');
    }
    // Mark denda sebagai lunas
    

   public function report(Request $request)
{
    // Kita ambil relasi lengkap: user peminjam dan details asetnya
    $query = Peminjaman::with(['user', 'details.asset']);

    // Filter Status (Sesuaikan dengan pilihan di View)
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Filter Tanggal
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
    }

    $history = $query->latest()->paginate(15);

    return view('admin.reports.index', compact('history')); // Sesuaikan nama view lo
}
public function complete($id)
{
    $peminjaman = Peminjaman::findOrFail($id);
    $now = Carbon::now();
    $endTime = Carbon::parse($peminjaman->end_time);

    // 1. Cek apakah telat
    if ($now->greaterThan($endTime)) {
        // Hitung selisih jam (pembulatan ke atas)
        $hoursLate = $now->diffInHours($endTime);
        if ($hoursLate == 0) $hoursLate = 1; // Jika telat menit, tetep itung 1 jam

        // Ambil harga denda dari database
        $penaltyRate = Setting::where('key', 'penalty_per_hour')->value('value') ?? 5000;
        $totalPenalty = $hoursLate * $penaltyRate;

        // 2. Buat record denda otomatis
        Penalty::create([
            'user_id' => $peminjaman->user_id,
            'peminjaman_id' => $peminjaman->id,
            'amount' => $totalPenalty,
            'description' => "Terlambat mengembalikan selama {$hoursLate} jam.",
            'status' => 'unpaid'
        ]);
        
        $msg = "Aset dikembalikan. Denda otomatis Rp " . number_format($totalPenalty) . " ditambahkan.";
    } else {
        $msg = "Aset dikembalikan tepat waktu. Mantap!";
    }

    // 3. Update status peminjaman
    $peminjaman->update([
        'status' => 'completed',
        'actual_return_time' => $now
    ]);

    // Kembalikan stok (Logika stok lo yang lama...)
    foreach ($peminjaman->details as $detail) {
        $detail->asset->increment('quantity', $detail->quantity);
    }

    return back()->with('success', $msg);
}
public function settings() {
    $penaltyRate = DB::table('settings')->where('key', 'penalty_per_hour')->value('value');
    return view('admin.settings', compact('penaltyRate'));
}

public function updateSettings(Request $request) {
    DB::table('settings')->where('key', 'penalty_per_hour')->update([
        'value' => $request->penalty_per_hour,
        'updated_at' => now()
    ]);
    return back()->with('success', 'Harga denda berhasil diupdate!');
}
public function paid($id)
{
    $penalty = Penalty::findOrFail($id);

    // Update status jadi paid
    $penalty->update([
        'status' => 'paid',
        'description' => $penalty->description . ' (Lunas Verified by Admin)'
    ]);

    return back()->with('success', 'Denda mahasiswa berhasil dilunaskan!');
}

}