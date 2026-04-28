<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index()
    {
        $prodis = Prodi::withCount('assets')->get();
        return view('admin.prodis.index', compact('prodis'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama_prodi' => 'required|unique:prodis,nama_prodi']);
        Prodi::create(['nama_prodi' => $request->nama_prodi]);
        return redirect()->back()->with('success', 'Prodi berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $prodi = Prodi::findOrFail($id);
        if ($prodi->assets()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal! Masih ada aset yang terikat di prodi ini.');
        }
        $prodi->delete();
        return redirect()->back()->with('success', 'Prodi berhasil dihapus.');
    }
}