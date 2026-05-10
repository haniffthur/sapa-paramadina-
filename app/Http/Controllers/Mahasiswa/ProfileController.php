<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Tampilkan Form
    public function showCompleteForm()
    {
        // Kalo ternyata NIM & Prodi udah diisi, tendang ke Dashboard (biar gak iseng buka)
        if (!empty(Auth::user()->nim) && !empty(Auth::user()->prodi_id)) {
            return redirect()->route('dashboard');
        }

        $prodis = Prodi::all();
        return view('auth.complete_profile', compact('prodis'));
    }

    // Proses Simpan
    public function updateProfile(Request $request)
    {
        $request->validate([
            'nim' => 'required|numeric|unique:users,nim,' . Auth::id(),
            'prodi_id' => 'required|exists:prodis,id',
        ], [
            'nim.required' => 'NIM tidak boleh kosong!',
            'nim.unique' => 'NIM ini sudah terdaftar!',
            'prodi_id.required' => 'Pilih Program Studi lo!'
        ]);

        Auth::user()->update([
            'nim' => $request->nim,
            'prodi_id' => $request->prodi_id,
        ]);

        // Kalau sukses, langsung lempar ke dashboard utama
        return redirect()->route('dashboard')->with('success', 'Mantap! Profil lo udah lengkap.');
    }
}