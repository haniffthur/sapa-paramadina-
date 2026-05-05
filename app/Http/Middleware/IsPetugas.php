<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsPetugas
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'petugas') {
            return $next($request);
        }

        // Kalau bukan petugas, tendang ke dashboard masing-masing atau ke halaman login
        return redirect('/dashboard')->with('error', 'Akses ditolak! Halaman ini khusus Petugas.');
    }
}