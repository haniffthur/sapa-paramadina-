<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  

class ProdiController extends Controller
{
    // 1. Nampilin semua data Prodi
    public function index()
    {
        // Ambil semua prodi sekalian ngitung jumlah ruangannya
        $prodis = Prodi::withCount('rooms')->latest()->get();

        // Hitung total aset per prodi lewat relasi Many-to-Many
        foreach ($prodis as $prodi) {
            $roomIds = $prodi->rooms()->pluck('rooms.id');
            $prodi->assets_count = Asset::whereIn('room_id', $roomIds)->count();
        }

        return view('admin.prodis.index', compact('prodis'));
    }

    // 2. Nampilin form buat Prodi baru
    public function create()
    {
        return view('admin.prodis.create');
    }

    // 3. Simpan data Prodi baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_prodi' => 'required|string|max:255|unique:prodis,nama_prodi',
        ], [
            'nama_prodi.unique' => 'Nama prodi ini sudah terdaftar bro!'
        ]);

        Prodi::create([
            'nama_prodi' => $request->nama_prodi,
        ]);

        return redirect()->route('admin.prodis.index')->with('success', 'Prodi berhasil ditambahkan!');
    }

    // 4. Nampilin form edit Prodi
    public function edit($id)
    {
        $prodi = Prodi::findOrFail($id);
        return view('admin.prodis.edit', compact('prodi'));
    }

    // 5. Simpan perubahan Prodi
    public function update(Request $request, $id)
    {
        $prodi = Prodi::findOrFail($id);

        $request->validate([
            // Validasi unique dikecualikan buat ID prodi yang lagi diedit
            'nama_prodi' => 'required|string|max:255|unique:prodis,nama_prodi,' . $prodi->id,
        ]);

        $prodi->update([
            'nama_prodi' => $request->nama_prodi,
        ]);

        return redirect()->route('admin.prodis.index')->with('success', 'Data prodi berhasil diupdate!');
    }

    // 6. Hapus Prodi
    public function destroy($id)
    {
        $prodi = Prodi::findOrFail($id);
        
        // JURUS AMAN: Putusin dulu semua koneksi ke ruangan sebelum dihapus
        $prodi->rooms()->detach();
        
        // Baru hapus prodinya
        $prodi->delete();

        return redirect()->route('admin.prodis.index')->with('success', 'Prodi berhasil dihapus permanen!');
    }
}