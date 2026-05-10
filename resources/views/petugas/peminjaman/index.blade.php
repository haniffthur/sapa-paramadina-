@extends('layouts.petugas')

@section('content')
<div class="px-5 pt-6 pb-2">
    <h2 class="text-2xl font-black text-slate-800 tracking-tight">Pantau Fasilitas</h2>
    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Monitor semua status peminjaman mahasiswa</p>
</div>

<form action="{{ route('petugas.peminjaman.index') }}" method="GET" class="px-5 mb-5 flex gap-2">
    <div class="relative flex-1">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari peminjam / aset..." 
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
        <a href="{{ route('petugas.peminjaman.index') }}" class="bg-red-50 text-red-500 w-10 shrink-0 rounded-2xl flex items-center justify-center shadow-sm border border-red-100 active:scale-95 transition-all">
            <i class="fa-solid fa-rotate-right text-xs"></i>
        </a>
    @endif
</form>

<div class="px-5 pb-12 space-y-4">
    
    @forelse($peminjamans as $loan)
    <div class="bg-white rounded-[2rem] p-5 border border-slate-100 shadow-sm relative overflow-hidden group">
        
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 font-black flex items-center justify-center border border-slate-100 shrink-0">
                    {{ substr($loan->user->name, 0, 1) }}
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-800 leading-tight">{{ $loan->user->name }}</h4>
                    <p class="text-[10px] font-medium text-slate-400 uppercase tracking-tighter mt-0.5">NIM: {{ $loan->user->nim ?? '-' }}</p>
                </div>
            </div>
            
            <div>
                @if($loan->status == 'pending')
                    <span class="bg-orange-50 text-orange-500 border border-orange-100 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest shadow-sm">Pending</span>
                @elseif($loan->status == 'active' || $loan->status == 'approved')
                    <span class="bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest shadow-sm">Dipinjam</span>
                @elseif($loan->status == 'completed' || $loan->status == 'selesai')
                    <span class="bg-green-50 text-green-600 border border-green-100 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest shadow-sm">Selesai</span>
                @else
                    <span class="bg-red-50 text-red-500 border border-red-100 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest shadow-sm animate-pulse">Telat</span>
                @endif
            </div>
        </div>

        <div class="bg-slate-50 rounded-[1.5rem] p-4 border border-slate-100">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Aset Utama</p>
                    <h5 class="text-xs font-bold text-slate-800 mt-1">
                        {{ $loan->details->first()?->asset?->name ?? 'Aset' }}
                        @if($loan->details->count() > 1)
                            <span class="text-[9px] text-blue-600 bg-blue-100 px-1.5 py-0.5 rounded-md ml-1">+{{ $loan->details->count() - 1 }} Item</span>
                        @endif
                    </h5>
                </div>
                <span class="text-[10px] font-bold text-blue-700 bg-blue-100/50 px-2 py-1 rounded-lg border border-blue-100">
                    <i class="fa-solid fa-location-dot"></i> {{ $loan->details->first()?->asset?->room->name ?? 'LAB' }}
                </span>
            </div>
            <div class="flex items-center gap-2 border-t border-slate-200 pt-3 mt-1">
                <i class="fa-regular fa-clock text-slate-400 text-[10px]"></i>
                <span class="text-[10px] font-bold text-slate-500 uppercase">
                    {{ \Carbon\Carbon::parse($loan->start_time)->format('d M, H:i') }} - {{ \Carbon\Carbon::parse($loan->end_time)->format('H:i') }} WIB
                </span>
            </div>
        </div>

        <div class="mt-4">
            @if($loan->status == 'completed' || $loan->status == 'selesai' || $loan->status == 'late')
                
                {{-- Cek apakah sudah ada laporan di database untuk peminjaman ini --}}
                @if($loan->reports->count() > 0)
                    {{-- DISABLE KALO SUDAH LAPOR --}}
                    <button type="button" disabled class="w-full bg-slate-100 border border-slate-200 text-slate-400 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-2 cursor-not-allowed">
                        <i class="fa-solid fa-check-double text-[10px]"></i> Sudah Dilaporkan
                    </button>
                @else
                    {{-- AKTIF KALO BELUM LAPOR --}}
                    <button type="button" onclick="openModal('reportModal-{{ $loan->id }}')" class="w-full bg-red-500 text-white py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest active:scale-95 transition-all flex items-center justify-center gap-2 shadow-lg shadow-red-500/20">
                        <i class="fa-solid fa-triangle-exclamation"></i> Lapor Kerusakan
                    </button>
                @endif

            @else
                {{-- DISABLE KALO STATUS BELUM SELESAI --}}
                <button type="button" disabled class="w-full bg-gray-50 border border-dashed border-gray-200 text-gray-400 py-3.5 rounded-2xl text-[10px] font-bold uppercase tracking-widest flex items-center justify-center gap-2 cursor-not-allowed">
                    <i class="fa-solid fa-lock text-[10px]"></i> Lapor (Tunggu Selesai)
                </button>
            @endif
        </div>
    </div>
    @empty
    
    <div class="bg-white py-12 rounded-[2.5rem] border border-dashed border-slate-200 text-center flex flex-col items-center">
        <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-3">
            <i class="fa-solid fa-mug-hot text-2xl text-blue-300"></i>
        </div>
        <h4 class="text-sm font-black text-slate-800">Kampus Terpantau Aman</h4>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Belum ada aktivitas peminjaman saat ini.</p>
    </div>
    @endforelse

    @if($peminjamans->hasPages())
    <div class="pt-4 pb-8">
        {{ $peminjamans->links() }}
    </div>
    @endif

</div>

{{-- MODAL LOOP --}}
@foreach($peminjamans as $loan)
    @if($loan->status == 'completed' || $loan->status == 'selesai' || $loan->status == 'late')
    <div id="reportModal-{{ $loan->id }}" class="fixed inset-0 z-[100] hidden flex items-end justify-center p-0 sm:p-4 sm:items-center">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity opacity-0" id="backdrop-reportModal-{{ $loan->id }}" onclick="closeModal('reportModal-{{ $loan->id }}')"></div>
        <div class="bg-white w-full sm:max-w-md rounded-t-[2.5rem] sm:rounded-[2.5rem] p-6 relative z-10 transform translate-y-full transition-transform duration-300 shadow-2xl" id="content-reportModal-{{ $loan->id }}">
            
            <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-6 sm:hidden"></div>
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="font-extrabold text-slate-800 text-xl tracking-tight">Lapor Kerusakan</h3>
                    <p class="text-xs font-medium text-slate-500 mt-1">Peminjam: {{ $loan->user->name }}</p>
                </div>
                <button type="button" onclick="closeModal('reportModal-{{ $loan->id }}')" class="w-8 h-8 bg-slate-100 text-slate-500 rounded-full flex items-center justify-center hover:bg-slate-200 active:scale-90 transition-all shrink-0">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form action="{{ route('petugas.report.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="peminjaman_id" value="{{ $loan->id }}">
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Pilih Aset Bermasalah</label>
                    <select name="asset_id" required class="w-full px-4 py-3.5 rounded-xl border border-slate-100 bg-slate-50 focus:ring-2 focus:ring-blue-900 outline-none transition font-semibold text-sm text-slate-700">
                        @foreach($loan->details as $detail)
                            <option value="{{ $detail->asset->id }}">{{ $detail->asset->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Upload Bukti Foto</label>
                    <div class="relative">
                        <input type="file" name="foto_kerusakan" accept="image/*" class="w-full px-4 py-3 rounded-xl border border-slate-100 bg-slate-50 text-xs font-semibold text-slate-500 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-red-100 file:text-red-700 hover:file:bg-red-200 transition cursor-pointer">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Deskripsi Kendala</label>
                    <textarea name="deskripsi_kerusakan" rows="3" required class="w-full px-4 py-3 rounded-xl border border-slate-100 bg-slate-50 focus:ring-2 focus:ring-blue-900 outline-none transition font-semibold text-sm text-slate-700" placeholder="Jelaskan kondisi barang saat diserahkan..."></textarea>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Usulan Denda (Rp)</label>
                    <input type="number" name="nominal_denda" placeholder="Misal: 50000" class="w-full px-4 py-3 rounded-xl border border-slate-100 bg-slate-50 focus:ring-2 focus:ring-blue-900 outline-none transition font-semibold text-sm text-slate-700">
                    <p class="text-[9px] text-slate-400 font-bold mt-1.5">*Kosongkan jika tidak perlu denda.</p>
                </div>

                <button type="submit" class="w-full bg-red-500 text-white font-extrabold text-sm py-4 rounded-2xl flex items-center justify-center gap-2 hover:bg-red-600 active:scale-95 transition-all shadow-lg shadow-red-500/20 mt-4">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Laporan
                </button>
            </form>
        </div>
    </div>
    @endif
@endforeach

<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if(!modal) return;
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