@extends('layouts.app')

@section('content')
<div class="px-5 py-6 space-y-8">
    
    <!-- Header / Hero Section -->
    <div class="relative rounded-[2.5rem] overflow-hidden aspect-[4/3] shadow-2xl shadow-blue-900/10 border-4 border-white">
        <!-- Background Image -->
       <img src="{{ asset('img/univ.jpg') }}" class="w-full h-full object-cover grayscale-[0.2] brightness-75">
        <!-- Overlay Gradient -->
        <div class="absolute inset-0 bg-gradient-to-t from-brand/95 via-brand/40 to-transparent"></div>
        
        <!-- Content Banner -->
        <div class="absolute bottom-6 left-6 right-6">
            <p class="text-[10px] font-bold text-blue-200 uppercase tracking-[0.3em] mb-1">SAPA Paramadina</p>
            <h1 class="text-2xl font-black text-white leading-tight">Akses Fasilitas<br>Lebih Mudah.</h1>
            <a href="{{ route('scan.area') }}" class="mt-4 inline-flex items-center gap-2 bg-white text-brand px-6 py-3 rounded-2xl text-[11px] font-black shadow-lg active:scale-95 transition-all">
                <i class="fa-solid fa-qrcode text-sm"></i>
                SCAN PINTU LAB
            </a>
        </div>
    </div>

    <!-- Alert Denda (Muncull otomatis kalau ada tagihan) -->
    @if($unpaid_penalty > 0)
    <div class="bg-red-500 rounded-[2rem] p-5 shadow-xl shadow-red-200 flex items-center justify-between relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full blur-xl"></div>
        <div class="relative z-10">
            <p class="text-[10px] font-black text-red-200 uppercase tracking-widest mb-1">Tunggakan Denda</p>
            <h3 class="text-xl font-black text-white leading-none">Rp {{ number_format($unpaid_penalty, 0, ',', '.') }}</h3>
        </div>
        <a href="{{ route('peminjaman.penalties') }}" class="relative z-10 w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white backdrop-blur-sm active:scale-90 transition-all">
            <i class="fa-solid fa-arrow-right text-sm"></i>
        </a>
    </div>
    @endif

    <!-- Aksi Cepat Section -->
    <section>
        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 px-1">Aksi Cepat</h3>
        <div class="grid grid-cols-4 gap-4">
            <!-- Menu Asset -->
             <a href="{{ route('peminjaman.assets') }}" class="flex flex-col items-center gap-2 group">
    
    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm border border-slate-50 group-active:scale-90 transition-transform
        {{ request()->routeIs('peminjaman.assets') 
            ? 'bg-brand text-white shadow-lg shadow-blue-200' 
            : 'bg-white text-brand' }}">
        
        <i class="fa-solid fa-box-open text-xl"></i>
    </div>

    <span class="text-[10px] font-extrabold
        {{ request()->routeIs('peminjaman.assets') 
            ? 'text-brand' 
            : 'text-slate-500' }}">
        Asset
    </span>

</a>
            
            <!-- Menu Riwayat -->
            <a href="{{ route('peminjaman.history') }}" class="flex flex-col items-center gap-2 group">
                <div class="w-14 h-14 bg-white rounded-[1.2rem] flex items-center justify-center text-brand shadow-sm border border-slate-100 active:scale-90 transition-transform">
                    <i class="fa-solid fa-clock-rotate-left text-xl"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-500">Riwayat</span>
            </a>

            <!-- Menu Denda -->
            <a href="{{ route('peminjaman.penalties') }}" class="flex flex-col items-center gap-2 group">
                <div class="w-14 h-14 bg-white rounded-[1.2rem] flex items-center justify-center text-brand shadow-sm border border-slate-100 active:scale-90 transition-transform relative">
                    <i class="fa-solid fa-receipt text-xl"></i>
                    @if($unpaid_penalty > 0)
                        <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 border-2 border-white rounded-full"></span>
                    @endif
                </div>
                <span class="text-[10px] font-bold text-slate-500">Denda</span>
            </a>

            <!-- Menu Bantuan -->
            <a href="https://wa.me/6281234567890" target="_blank" class="flex flex-col items-center gap-2 group">
                <div class="w-14 h-14 bg-white rounded-[1.2rem] flex items-center justify-center text-brand shadow-sm border border-slate-100 active:scale-90 transition-transform">
                    <i class="fa-solid fa-headset text-xl"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-500">Bantuan</span>
            </a>
        </div>
    </section>

    <!-- Pinjaman Aktif Section -->
    <section class="space-y-4">
        <div class="flex items-center justify-between px-1">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Pinjaman Aktif</h3>
            <span class="text-[10px] font-bold text-brand bg-blue-50 px-3 py-1 rounded-lg border border-blue-100">{{ count($active_loans) }} Item</span>
        </div>

        <div class="space-y-3">
            @forelse($active_loans as $loan)
            <div class="bg-white p-4 rounded-3xl border border-slate-100 flex items-center justify-between shadow-sm group">
                <div class="flex items-center gap-4">
                    <!-- Icon Category -->
                    <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-brand border border-slate-100">
                        <i class="fa-solid {{ $loan->asset->category->name == 'Ruangan' ? 'fa-door-open' : 'fa-laptop-code' }} text-lg"></i>
                    </div>
                    <!-- Info -->
                    <div>
                        <h4 class="text-sm font-extrabold text-slate-800 leading-tight mb-0.5">{{ $loan->asset->name }}</h4>
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $loan->asset->room->name }}</p>
                        </div>
                    </div>
                </div>
                <!-- Tombol Selesai (Kembalikan) -->
                <form action="{{ route('peminjaman.selesai', $loan->id) }}" method="POST">
                    @csrf
                    <button class="bg-slate-50 text-slate-400 hover:text-red-500 hover:bg-red-50 w-11 h-11 rounded-xl flex items-center justify-center active:scale-90 transition-all border border-slate-100">
                        <i class="fa-solid fa-arrow-turn-down-left text-sm"></i>
                    </button>
                </form>
            </div>
            @empty
            <!-- Empty State -->
            <div class="bg-white py-12 rounded-[2.5rem] border border-dashed border-slate-200 text-center flex flex-col items-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                    <i class="fa-solid fa-mug-hot text-2xl text-slate-300"></i>
                </div>
                <h4 class="text-sm font-black text-slate-800">Lagi Santai</h4>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Belum ada fasilitas yang dipinjam.</p>
            </div>
            @endforelse
        </div>
    </section>

</div>
@endsection