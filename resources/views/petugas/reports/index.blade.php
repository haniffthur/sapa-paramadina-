@extends('layouts.petugas')

@section('content')
<div class="px-5 pt-6 pb-2">
    <h2 class="text-2xl font-black text-slate-800 tracking-tight">Riwayat Laporan</h2>
    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Pantau status perbaikan aset yang dilaporkan</p>
</div>

<form action="{{ route('petugas.reports.index') }}" method="GET" class="px-5 mb-5 flex gap-2">
    <div class="relative flex-1">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aset / ruangan..." 
               class="w-full bg-white border border-slate-100 rounded-2xl py-3 pl-9 pr-3 text-xs font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-900 shadow-sm placeholder:font-medium placeholder:text-slate-300">
    </div>
    
    <div class="relative w-[110px] shrink-0">
        <input type="date" name="date" value="{{ request('date') }}" 
               class="w-full bg-white border border-slate-100 rounded-2xl py-3 px-3 text-[10px] font-bold text-slate-500 outline-none focus:ring-2 focus:ring-blue-900 shadow-sm uppercase text-center appearance-none cursor-pointer">
    </div>

    <button type="submit" class="bg-blue-900 text-white w-12 shrink-0 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-900/20 active:scale-95 transition-all">
        <i class="fa-solid fa-filter text-xs"></i>
    </button>
    
    @if(request('search') || request('date'))
        <a href="{{ route('petugas.reports.index') }}" class="bg-red-50 text-red-500 w-10 shrink-0 rounded-2xl flex items-center justify-center shadow-sm border border-red-100 active:scale-95 transition-all">
            <i class="fa-solid fa-rotate-right text-xs"></i>
        </a>
    @endif
</form>
<div class="px-5 pb-12 space-y-4">

    @forelse($reports as $report)
    <div onclick="openModal('detailModal-{{ $report->id }}')" class="bg-white rounded-[2rem] p-5 border border-slate-100 shadow-sm relative overflow-hidden group cursor-pointer active:scale-95 transition-transform">
        
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                @if($report->status == 'menunggu')
                    <div class="w-10 h-10 rounded-[1rem] bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-clock-rotate-left text-lg"></i>
                    </div>
                @elseif($report->status == 'diproses')
                    <div class="w-10 h-10 rounded-[1rem] bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-screwdriver-wrench text-lg"></i>
                    </div>
                @else
                    <div class="w-10 h-10 rounded-[1rem] bg-green-50 text-green-500 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-check-double text-lg"></i>
                    </div>
                @endif
                
                <div>
                    <h4 class="text-sm font-bold text-slate-800 leading-tight">{{ $report->asset->name }}</h4>
                    <p class="text-[10px] font-medium text-blue-600 uppercase tracking-widest mt-0.5">
                        <i class="fa-solid fa-location-dot"></i> {{ $report->asset->room->name ?? 'LAB' }}
                    </p>
                </div>
            </div>
            
            <div>
                @if($report->status == 'menunggu')
                    <span class="bg-amber-50 text-amber-500 border border-amber-100 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest shadow-sm">Menunggu</span>
                @elseif($report->status == 'diproses')
                    <span class="bg-blue-50 text-blue-500 border border-blue-100 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest shadow-sm animate-pulse">Diproses</span>
                @else
                    <span class="bg-green-50 text-green-500 border border-green-100 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest shadow-sm">Selesai</span>
                @endif
            </div>
        </div>

        <div class="bg-slate-50 rounded-[1.5rem] p-4 border border-slate-100 mb-2">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Deskripsi Kendala</p>
            <p class="text-xs font-semibold text-slate-600 line-clamp-2 leading-relaxed italic">
                "{{ $report->deskripsi_kerusakan }}"
            </p>
        </div>

        <div class="flex items-center justify-between border-t border-slate-100 pt-3 mt-1">
            <div class="flex items-center gap-2">
                <i class="fa-regular fa-calendar text-slate-400 text-xs"></i>
                <span class="text-[10px] font-bold text-slate-500">
                    {{ $report->created_at->format('d M Y') }} • {{ $report->created_at->format('H:i') }}
                </span>
            </div>
            
            @if($report->foto_kerusakan)
                <div class="flex items-center gap-1.5 text-blue-600">
                    <i class="fa-solid fa-image text-xs"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">Lihat Foto</span>
                </div>
            @endif
        </div>
    </div>

    <div id="detailModal-{{ $report->id }}" class="fixed inset-0 z-[100] hidden flex items-end justify-center p-0 sm:p-4 sm:items-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0" id="backdrop-detailModal-{{ $report->id }}" onclick="closeModal('detailModal-{{ $report->id }}')"></div>
        
        <div class="bg-white w-full sm:max-w-md rounded-t-[2.5rem] sm:rounded-[2.5rem] p-6 relative z-10 transform translate-y-full transition-transform duration-300 shadow-2xl" id="content-detailModal-{{ $report->id }}">
            <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-6 sm:hidden"></div>
            
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="font-extrabold text-slate-800 text-xl tracking-tight">Detail Laporan</h3>
                    <p class="text-xs font-medium text-slate-500 mt-1">ID Laporan: #{{ $report->id }}</p>
                </div>
                <button type="button" onclick="closeModal('detailModal-{{ $report->id }}')" class="w-8 h-8 bg-slate-100 text-slate-500 rounded-full flex items-center justify-center hover:bg-slate-200 active:scale-90 transition-all shrink-0">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-1">
                
                @if($report->foto_kerusakan)
                    <div class="w-full h-48 rounded-[1.5rem] overflow-hidden border border-slate-100 shadow-sm relative">
                        <img src="{{ asset('storage/' . $report->foto_kerusakan) }}" alt="Foto Bukti" class="w-full h-full object-cover">
                        <a href="{{ asset('storage/' . $report->foto_kerusakan) }}" target="_blank" class="absolute bottom-3 right-3 w-8 h-8 bg-black/50 backdrop-blur-md rounded-lg flex items-center justify-center text-white">
                            <i class="fa-solid fa-expand text-xs"></i>
                        </a>
                    </div>
                @else
                    <div class="w-full h-24 rounded-[1.5rem] bg-slate-50 border border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
                        <i class="fa-solid fa-image-slash mb-2 text-xl"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest">Tidak Ada Foto Bukti</span>
                    </div>
                @endif

                <div class="bg-slate-50 rounded-[1.5rem] p-4 border border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Keterangan Laporan:</p>
                    <p class="text-sm font-semibold text-slate-700 leading-relaxed">
                        "{{ $report->deskripsi_kerusakan }}"
                    </p>
                </div>

                <div class="flex items-center gap-3 px-1">
                    <div class="flex-1">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Dilaporkan Pada</p>
                        <p class="text-xs font-bold text-slate-700">{{ $report->created_at->format('d F Y, H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Update Terakhir</p>
                        <p class="text-xs font-bold text-slate-700">{{ $report->updated_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>

            </div>

            <button type="button" onclick="closeModal('detailModal-{{ $report->id }}')" class="w-full bg-blue-900 text-white font-extrabold text-sm py-4 rounded-2xl flex items-center justify-center gap-2 hover:bg-black active:scale-95 transition-all mt-6 shadow-lg shadow-blue-900/20">
                Tutup Detail
            </button>
        </div>
    </div>
    @empty
    
    <div class="bg-white py-12 rounded-[2.5rem] border border-dashed border-slate-200 text-center flex flex-col items-center">
        <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-3">
            <i class="fa-solid fa-clipboard-check text-2xl text-blue-300"></i>
        </div>
        <h4 class="text-sm font-black text-slate-800">Belum Ada Laporan</h4>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Gunakan fitur ini untuk memantau aset rusak.</p>
    </div>
    @endforelse

    @if($reports->hasPages())
    <div class="pt-4 pb-8">
        {{ $reports->links() }}
    </div>
    @endif

</div>

<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        const backdrop = document.getElementById('backdrop-' + id);
        const content = document.getElementById('content-' + id);
        
        modal.classList.remove('hidden');
        void modal.offsetWidth; 
        backdrop.classList.remove('opacity-0');
        content.classList.remove('translate-y-full');
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        const backdrop = document.getElementById('backdrop-' + id);
        const content = document.getElementById('content-' + id);
        
        backdrop.classList.add('opacity-0');
        content.classList.add('translate-y-full');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection