@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen pb-24 font-sans">
    
    <!-- Hero Image Section -->
    <div class="relative w-full h-80 bg-slate-100 overflow-hidden">
        @if($asset->image)
            <img src="{{ asset('storage/' . $asset->image) }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-slate-50">
                <i class="fa-solid fa-box-open text-5xl mb-3 text-slate-200"></i>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Tidak Ada Foto</span>
            </div>
        @endif

        <!-- Gradient Overlay for smooth transition -->
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>

        <!-- Tombol Back Floating -->
        <a href="{{ route('peminjaman.assets') }}" class="absolute top-6 left-5 w-11 h-11 bg-white/20 backdrop-blur-xl rounded-full flex items-center justify-center text-white shadow-sm border border-white/30 hover:bg-white/30 active:scale-90 transition-all z-10">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
    </div>

    <!-- Content Section (Overlapping the image) -->
    <div class="relative z-20 -mt-12 bg-white rounded-t-[2.5rem] px-6 py-8 shadow-[0_-10px_40px_rgba(0,0,0,0.05)]">
        
        <!-- Top Badges -->
        <div class="flex items-center gap-2 mb-4">
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-brand text-[9px] font-black uppercase tracking-widest">
                <i class="fa-solid fa-tag"></i> {{ $asset->category->name ?? 'Umum' }}
            </div>
            
            @if($asset->quantity > 0)
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-green-50 border border-green-100 text-green-600 text-[9px] font-black uppercase tracking-widest">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Tersedia
                </div>
            @else
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-50 border border-red-100 text-red-500 text-[9px] font-black uppercase tracking-widest">
                    Habis
                </div>
            @endif
        </div>

        <!-- Title & Qty -->
        <div class="flex items-start justify-between mb-8">
            <h1 class="text-3xl font-black text-slate-800 leading-tight pr-4">{{ $asset->name }}</h1>
            
            <div class="text-right shrink-0 pt-1">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Sisa Stok</p>
                <div class="flex items-baseline justify-end gap-1 text-brand">
                    <span class="text-3xl font-black leading-none">{{ $asset->quantity }}</span>
                    <span class="text-xs font-bold">Unit</span>
                </div>
            </div>
        </div>

        <!-- Spesifikasi Detail (List Style) -->
        <div class="space-y-3 mb-8">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1 mb-2">Informasi Aset</h3>
            
            <!-- Lokasi Card -->
            <div class="flex items-center p-4 rounded-2xl border border-slate-100 bg-white shadow-[0_4px_20px_rgba(0,0,0,0.02)]">
                <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 mr-4 border border-slate-100">
                    <i class="fa-solid fa-location-dot text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Lokasi Penyimpanan</p>
                    <p class="text-sm font-extrabold text-slate-800">{{ $asset->room->name }}</p>
                </div>
            </div>

            <!-- Kondisi Card -->
            <div class="flex items-center p-4 rounded-2xl border border-slate-100 bg-white shadow-[0_4px_20px_rgba(0,0,0,0.02)]">
                <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 mr-4 border border-slate-100">
                    <i class="fa-solid fa-clipboard-check text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Kondisi Barang</p>
                    <p class="text-sm font-extrabold text-slate-800 capitalize">{{ $asset->kondisi ?? 'Berfungsi dengan Baik' }}</p>
                </div>
            </div>
        </div>

        <!-- Deskripsi Ruangan Dinamis -->
        <div class="mb-10 px-1">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Deskripsi Ruangan</h3>
            <p class="text-sm font-medium text-slate-500 leading-relaxed">
                {{ $asset->room->description ?? 'Tidak ada deskripsi yang tersedia untuk ruangan ini.' }}
            </p>
        </div>

        <!-- Premium CTA Card -->
        <div class="bg-slate-900 rounded-[2rem] p-6 text-white shadow-2xl shadow-slate-900/20 relative overflow-hidden">
            <!-- Glass/Lighting Effect -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-brand blur-[50px] opacity-40"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-blue-500 blur-[40px] opacity-20"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center">
                        <i class="fa-solid fa-camera text-blue-400 text-xs"></i>
                    </div>
                    <h3 class="text-sm font-black tracking-wide">Mulai Peminjaman</h3>
                </div>
                
                <p class="text-[11px] text-slate-300 leading-relaxed font-medium mb-6">
                    Aset ini tidak dapat dipinjam dari sini. Silakan berjalan ke pintu <strong class="text-white">{{ $asset->room->name }}</strong> dan tekan tombol di bawah untuk memindai QR Code ruangan.
                </p>
                
                <a href="{{ route('scan.area') }}" class="w-full bg-white text-slate-900 py-4 rounded-2xl text-xs font-black uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-slate-50 active:scale-95 transition-all">
                    <i class="fa-solid fa-qrcode text-sm"></i> Buka Scanner QR
                </a>
            </div>
        </div>

    </div>
</div>
@endsection