<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class GoogleController extends Controller
{
    // MENAMPILKAN HALAMAN LOGIN
    public function index()
    {
        return view('auth.login');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
{
    try {
        // 1. Ambil data user dari Google
        $userGoogle = Socialite::driver('google')->user();
        $email = $userGoogle->getEmail();

        // 2. Validasi Domain (Khusus Paramadina)
        // Mahasiswa: @students.paramadina.ac.id
        // Admin/Dosen: @paramadina.ac.id
        if (!Str::endsWith($email, ['@paramadina.ac.id', '@students.paramadina.ac.id'])) {
           return view('auth.error_domain');
        }

        // 3. Cari User di Database berdasarkan email
        $user = User::where('email', $email)->first();

        if ($user) {
            // Jika user sudah ada, update data terbaru (avatar/nama)
            $user->update([
                'google_id' => $userGoogle->getId(),
                'avatar'    => $userGoogle->getAvatar(),
            ]);
        } else {
            // Jika user baru pertama kali login
            // Tentukan role otomatis berdasarkan email
            $role = Str::contains($email, 'students') ? 'mahasiswa' : 'admin';

            $user = User::create([
                'name'      => $userGoogle->getName(),
                'email'     => $email,
                'google_id' => $userGoogle->getId(),
                'avatar'    => $userGoogle->getAvatar(),
                'role'      => $role,
                'password'  => Hash::make(Str::random(24)), // Password random buat keamanan
            ]);
        }

        // 4. Login-kan User ke sistem Laravel
        Auth::login($user);

        // 5. REDIRECT MAGIC
        // intended() akan mengecek apakah ada URL yang mau dibuka user sebelum login
        // Misalnya: Mahasiswa scan QR -> Laravel lempar ke Login -> Login Sukses
        // -> Laravel otomatis lempar ke halaman Scan tadi (Intended).
        // Jika tidak ada, maka defaultnya ke route 'dashboard'.
        
        if ($user->role === 'admin') {
            // Kalau Admin, lempar ke dashboard Admin
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($user->role === 'petugas') {
            // Kalau Petugas, lempar ke dashboard Petugas
            return redirect()->intended(route('petugas.dashboard'));
        }

        return redirect()->intended(route('dashboard'));

    } catch (\Exception $e) {
        // Log error jika diperlukan: \Log::error($e->getMessage());
        return redirect()->route('login')->with('error', 'Terjadi kesalahan saat login Google.');
    }
}

    // FUNGSI LOGOUT
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}