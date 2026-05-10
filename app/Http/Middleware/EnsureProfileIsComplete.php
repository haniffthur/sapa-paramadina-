<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'mahasiswa') {
            
            // Cek apakah NIM atau Prodi KOSONG
            if (empty(Auth::user()->nim) || empty(Auth::user()->prodi_id)) {
                
                // PENGECUALIAN: Biarkan mereka ngakses form isi profil dan tombol logout
                if ($request->routeIs('profile.complete') || $request->routeIs('profile.update') || $request->routeIs('logout')) {
                    return $next($request);
                }

                // Kalo maksa akses halaman lain (kayak /dashboard), TENDANG BALIK!
                return redirect()->route('profile.complete')
                    ->with('error', 'Wajib isi NIM dan Program Studi sebelum masuk ke Dashboard!');
            }
        }

        return $next($request);
    }
}