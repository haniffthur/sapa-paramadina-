@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-md">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">Profil Saya</h2>
    </div>

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 text-center mb-6">
        <div class="relative inline-block mb-4">
            <img src="{{ Auth::user()->avatar }}" class="w-24 h-24 rounded-full border-4 border-blue-50 shadow-md mx-auto">
            <div class="absolute bottom-0 right-0 bg-green-500 w-6 h-6 rounded-full border-4 border-white"></div>
        </div>
        <h3 class="text-xl font-bold text-gray-800">{{ Auth::user()->name }}</h3>
        <p class="text-xs text-gray-500 uppercase font-bold tracking-widest mt-1">{{ Auth::user()->role }}</p>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-blue-900 text-white p-4 rounded-2xl text-center shadow-lg shadow-blue-100">
            <p class="text-[10px] opacity-70 uppercase font-bold">Total Pinjam</p>
            <p class="text-2xl font-black">
                {{ \App\Models\Peminjaman::where('user_id', Auth::id())->count() }}
            </p>
        </div>
        <div class="bg-white p-4 rounded-2xl text-center border border-gray-100">
            <p class="text-[10px] text-gray-400 uppercase font-bold">Prodi</p>
            <p class="text-xs font-bold text-gray-700 mt-1 truncate">
                {{ Str::limit(Auth::user()->email, 20) }}
            </p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-4 border-b border-gray-50 flex items-center gap-4">
            <i class="fa-solid fa-envelope text-blue-500 w-5"></i>
            <div class="text-sm">
                <p class="text-[10px] text-gray-400 font-bold uppercase">Email Institusi</p>
                <p class="font-semibold text-gray-700">{{ Auth::user()->email }}</p>
            </div>
        </div>
        
        <a href="#" class="p-4 border-b border-gray-50 flex items-center gap-4 hover:bg-gray-50">
            <i class="fa-solid fa-clock-rotate-left text-blue-500 w-5"></i>
            <span class="text-sm font-semibold text-gray-700">Riwayat Peminjaman</span>
            <i class="fa-solid fa-chevron-right ml-auto text-gray-300 text-xs"></i>
        </a>

        <a href="https://wa.me/628123456789" target="_blank" class="p-4 border-b border-gray-50 flex items-center gap-4 hover:bg-gray-50">
            <i class="fa-solid fa-headset text-blue-500 w-5"></i>
            <span class="text-sm font-semibold text-gray-700">Bantuan Laboran</span>
            <i class="fa-solid fa-chevron-right ml-auto text-gray-300 text-xs"></i>
        </a>
    </div>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="w-full bg-red-50 text-red-600 py-4 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 hover:bg-red-600 hover:text-white transition">
            <i class="fa-solid fa-right-from-bracket"></i> Keluar dari Aplikasi
        </button>
    </form>
</div>
@endsection