@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-md">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">Profil Saya</h2>
    </div>

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 text-center mb-6">
        <div class="relative inline-block mb-4">
            <img src="{{ Auth::user()->avatar ?? asset('default-avatar.png') }}" class="w-24 h-24 rounded-full border-4 border-blue-50 shadow-md mx-auto object-cover">
            <div class="absolute bottom-0 right-0 bg-green-500 w-6 h-6 rounded-full border-4 border-white"></div>
        </div>
        <h3 class="text-xl font-bold text-gray-800">{{ Auth::user()->name }}</h3>
        
        <p class="text-sm font-black text-blue-600 tracking-wider mt-1">{{ Auth::user()->nim ?? 'NIM Belum Diisi' }}</p>
        
        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mt-1">{{ Auth::user()->role }}</p>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-blue-900 text-white p-4 rounded-2xl text-center shadow-lg shadow-blue-100 flex flex-col justify-center">
            <p class="text-[10px] opacity-70 uppercase font-bold">Total Pinjam</p>
            <p class="text-2xl font-black mt-1">
                {{ \App\Models\Peminjaman::where('user_id', Auth::id())->count() }}
            </p>
        </div>
        <div class="bg-white p-4 rounded-2xl text-center border border-gray-100 flex flex-col justify-center">
            <p class="text-[10px] text-gray-400 uppercase font-bold">Prodi</p>
           <p class="text-xs font-bold text-gray-700 mt-1 line-clamp-2">
    {{ Auth::user()->prodi ? Auth::user()->prodi->nama_prodi : 'Belum ada Prodi' }}
</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-4 border-b border-gray-50 flex items-center gap-4">
            <i class="fa-solid fa-envelope text-blue-500 w-5 text-center"></i>
            <div class="text-sm">
                <p class="text-[10px] text-gray-400 font-bold uppercase">Email Institusi</p>
                <p class="font-semibold text-gray-700">{{ Auth::user()->email }}</p>
            </div>
        </div>
        
        <a href="{{ route('peminjaman.history') }}" class="p-4 border-b border-gray-50 flex items-center gap-4 hover:bg-gray-50 transition">
            <i class="fa-solid fa-clock-rotate-left text-blue-500 w-5 text-center"></i>
            <span class="text-sm font-semibold text-gray-700">Riwayat Peminjaman</span>
            <i class="fa-solid fa-chevron-right ml-auto text-gray-300 text-xs"></i>
        </a>

        <a href="https://wa.me/6285780498549" target="_blank" class="p-4 border-b border-gray-50 flex items-center gap-4 hover:bg-gray-50 transition">
            <i class="fa-solid fa-headset text-blue-500 w-5 text-center"></i>
            <span class="text-sm font-semibold text-gray-700">Bantuan Laporan</span>
            <i class="fa-solid fa-chevron-right ml-auto text-gray-300 text-xs"></i>
        </a>
    </div>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="w-full bg-red-50 text-red-600 py-4 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 hover:bg-red-600 hover:text-white transition active:scale-95 shadow-sm">
            <i class="fa-solid fa-right-from-bracket"></i> Keluar dari Aplikasi
        </button>
    </form>
</div>
@endsection