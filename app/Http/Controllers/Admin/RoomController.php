<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    // 1. Menampilkan semua data ruangan
    public function index()
    {
        $rooms = Room::with('prodis')->latest()->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    // 2. Menampilkan form tambah ruangan
    public function create()
    {
        $prodis = Prodi::all();
        return view('admin.rooms.create', compact('prodis'));
    }

    // 3. Menyimpan data ruangan baru (Bisa pilih Multiple Prodi)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prodi_ids' => 'required|array', // Validasi input berupa array
            'prodi_ids.*' => 'exists:prodis,id',
            'description' => 'nullable|string',
        ]);

        // Generate Token QR Pintu (Cukup sekali per ruangan)
        $token = 'ROOM-' . strtoupper(Str::random(6));

        // Create ruangannya dulu
        $room = Room::create([
            'name' => $request->name,
            'qr_code_token' => $token,
            'description' => $request->description,
        ]);

        // Sambungkan ruangan ini ke banyak prodi (Insert ke tabel Pivot)
        $room->prodis()->attach($request->prodi_ids);

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil dibuat dan disematkan ke prodi terkait!');
    }

    // 4. Menampilkan detail ruangan dan Cetak QR Code
    public function show($id)
    {
        $room = Room::with(['assets.category', 'prodis'])->findOrFail($id);
        
        // URL yang akan ditanam di QR Pintu
        $qrUrl = route('scan.room', $room->qr_code_token);

        return view('admin.rooms.show', compact('room', 'qrUrl'));
    }

    // 5. Menampilkan form edit ruangan
    public function edit($id)
    {
        $room = Room::with('prodis')->findOrFail($id);
        $prodis = Prodi::all();
        
        // Ambil array ID prodi yang udah nempel di ruangan ini 
        // (Biar di form HTML-nya otomatis ter-select)
        $selectedProdis = $room->prodis->pluck('id')->toArray();
        
        return view('admin.rooms.edit', compact('room', 'prodis', 'selectedProdis'));
    }

    // 6. Menyimpan perubahan data ruangan
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prodi_ids' => 'required|array',
            'prodi_ids.*' => 'exists:prodis,id',
            'description' => 'nullable|string',
        ]);

        $room = Room::findOrFail($id);
        
        // Update data utama ruangan
        $room->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // JURUS SAKTI: Sync data relasi prodi 
        // (Otomatis ngehapus yang gak dipilih & nyimpen centang prodi yang baru)
        $room->prodis()->sync($request->prodi_ids);

        return redirect()->route('admin.rooms.index')->with('success', 'Data ruangan berhasil diupdate!');
    }

    // 7. Menghapus data ruangan
    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        
        // Putus dulu relasinya dari prodi (Biar rapi databasenya)
        $room->prodis()->detach();
        
        // Baru hapus fisik ruangannya
        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil dihapus permanen!');
    }
}