<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login DAN rolenya admin atau superadmin
        if (Auth::check() && (Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin')) {
            return $next($request);
        }

        // Kalau bukan admin, balikkan ke dashboard mahasiswa dengan pesan error
        return redirect()->route('dashboard')->with('error', 'Akses ditolak! Khusus Admin.');
    }
}