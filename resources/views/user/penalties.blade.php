@extends('layouts.app')

@section('content')
<div class="px-5 py-6 space-y-6">
    
    <!-- Header Navigasi -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center text-slate-400 shadow-sm border border-slate-100 active:scale-90 transition-all">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight leading-none">Tagihan Denda</h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Kewajiban Administrasi</p>
        </div>
    </div>

    <!-- Summary Box -->
    @if($penalties->where('status', 'unpaid')->count() > 0)
    <div class="bg-red-500 rounded-[2rem] p-6 shadow-xl shadow-red-200 relative overflow-hidden">
        <!-- Dekorasi Background -->
        <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-red-200 uppercase tracking-widest mb-1">Total Belum Dibayar</p>
                <h3 class="text-3xl font-black text-white leading-none">
                    Rp {{ number_format($penalties->where('status', 'unpaid')->sum('amount'), 0, ',', '.') }}
                </h3>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="fa-solid fa-triangle-exclamation text-white text-xl"></i>
            </div>
        </div>
    </div>
    @endif

    <!-- List Denda -->
    <div class="space-y-4 pt-4">
        @forelse($penalties as $penalty)
        <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ $penalty->status == 'unpaid' ? 'bg-red-50 text-red-500' : 'bg-green-50 text-green-500' }}">
                    <i class="fa-solid {{ $penalty->status == 'unpaid' ? 'fa-receipt' : 'fa-check-double' }} text-lg"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-slate-800 text-base leading-none mb-1">Rp {{ number_format($penalty->amount, 0, ',', '.') }}</h4>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($penalty->created_at)->format('d M Y') }}</p>
                </div>
            </div>

            <!-- Status Label -->
            <div>
                @if($penalty->status == 'unpaid')
                    <span class="bg-red-50 text-red-500 px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border border-red-100">Belum Lunas</span>
                @else
                    <span class="bg-green-50 text-green-600 px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border border-green-100">Lunas</span>
                @endif
            </div>
        </div>
        @empty
        <!-- Empty State Denda -->
        <div class="py-20 flex flex-col items-center justify-center text-center">
            <div class="w-24 h-24 bg-white rounded-[2.5rem] flex items-center justify-center shadow-sm border border-slate-50 mb-4 relative">
                <i class="fa-solid fa-shield-halved text-4xl text-green-400"></i>
                <div class="absolute top-0 right-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center translate-x-1 -translate-y-1">
                    <i class="fa-solid fa-check text-green-600 text-[10px]"></i>
                </div>
            </div>
            <h3 class="text-sm font-black text-slate-800">Aman Terkendali!</h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Lo nggak punya tagihan denda apapun.</p>
        </div>
        @endforelse
    </div>

</div>
@endsection