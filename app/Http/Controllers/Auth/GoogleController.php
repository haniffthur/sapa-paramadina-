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
        $googleUser = Socialite::driver('google')->user();
        $email = $googleUser->getEmail();

        // 1. Cek apakah user sudah terdaftar di database kita
        $user = User::where('email', $email)->first();

        if (!$user) {
            // 2. Jika belum terdaftar, tentukan role secara ketat
            $domain = substr(strrchr($email, "@"), 1);
            
            // Default role adalah mahasiswa jika pakai domain student
            $role = 'mahasiswa';

            // Jika pakai domain @paramadina.ac.id (Himpunan/Dosen), 
            // JANGAN langsung kasih Admin. Kasih role 'mahasiswa' atau 'guest' dulu.
            // Biar nanti Admin utama yang ubah role mereka secara manual di menu User Management.
            if ($domain === 'paramadina.ac.id' || $domain === 'students.paramadina.ac.id') {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $email,
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'role' => 'mahasiswa', // <--- KUNCINYA DI SINI: Semua pendaftar baru adalah mahasiswa
                    'password' => bcrypt(str()->random(16)),
                ]);
            } else {
                return redirect()->route('login')->with('error', 'Gunakan email @paramadina.ac.id!');
            }
        }

        // 3. Login-kan user
        Auth::login($user);

        // 4. Redirect berdasarkan role yang ADA DI DATABASE, bukan berdasarkan email
        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($user->role === 'petugas') {
            return redirect()->intended(route('petugas.dashboard'));
        } else {
            // Mahasiswa, Dosen, atau Himpunan masuk ke sini dulu
            // Cek kelengkapan NIM/Prodi seperti yang kita buat sebelumnya
            if (empty($user->nim) || empty($user->prodi_id)) {
                return redirect()->route('profile.complete');
            }
            return redirect()->intended(route('dashboard'));
        }

    } catch (\Exception $e) {
        return redirect()->route('login')->with('error', 'Login gagal!');
    }
}

    // FUNGSI LOGOUT
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}