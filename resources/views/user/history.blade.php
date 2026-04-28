@extends('layouts.app')

@section('content')
<div class="px-5 py-6 space-y-6">
    
    <!-- Header Navigasi -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center text-slate-400 shadow-sm border border-slate-100 active:scale-90 transition-all">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight leading-none">Riwayat</h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Aktivitas Peminjaman</p>
        </div>
    </div>

    <!-- List Riwayat -->
    <div class="space-y-4">
        @forelse($history as $item)
        <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm flex flex-col gap-4">
            
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-brand">
                        <i class="fa-solid {{ $item->asset->category->name == 'Ruangan' ? 'fa-door-open' : 'fa-box-archive' }} text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-slate-800 text-sm leading-tight">{{ $item->asset->name }}</h4>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $item->asset->room->name }}</p>
                    </div>
                </div>
                
                <!-- Badge Status -->
                @if($item->status == 'returned')
                    <span class="bg-green-50 text-green-600 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border border-green-100">Selesai</span>
                @elseif($item->status == 'active' || $item->status == 'pending')
                    <span class="bg-blue-50 text-brand px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border border-blue-100">Aktif</span>
                @else
                    <span class="bg-red-50 text-red-500 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border border-red-100">Telat</span>
                @endif
            </div>

            <!-- Detail Waktu -->
            <div class="bg-[#F2F5F9] rounded-2xl p-3 flex justify-between items-center mt-2">
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Tanggal Pinjam</p>
                    <p class="text-xs font-black text-slate-700">{{ \Carbon\Carbon::parse($item->start_time)->format('d M Y, H:i') }}</p>
                </div>
                <div class="w-px h-6 bg-slate-200"></div>
                <div class="text-right">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Dikembalikan</p>
                    <p class="text-xs font-black text-slate-700">
                        {{ $item->returned_at ? \Carbon\Carbon::parse($item->returned_at)->format('d M Y, H:i') : '-' }}
                    </p>
                </div>
            </div>

        </div>
        @empty
        <!-- Empty State -->
        <div class="py-20 flex flex-col items-center justify-center text-center">
            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-50 mb-4">
                <i class="fa-solid fa-clock-rotate-left text-3xl text-slate-200"></i>
            </div>
            <h3 class="text-sm font-black text-slate-800">Belum ada riwayat</h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Lo belum pernah pinjam apapun.</p>
        </div>
        @endforelse
    </div>

</div>
@endsection