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
        
        <!-- CARD RIWAYAT BISA DI-KLIK -->
        <div onclick="openModal('modal-{{ $item->id }}')" class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm flex flex-col gap-4 cursor-pointer active:scale-95 transition-transform">
            
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-brand shrink-0">
                        <i class="fa-solid {{ ($item->details->first()?->asset?->category?->name == 'Ruangan') ? 'fa-door-open' : 'fa-box-archive' }} text-lg"></i>
                    </div>
                    <div>
                        <!-- Nampilin Nama Barang Pertama & Badge Sisa Barang -->
                        <h4 class="font-extrabold text-slate-800 text-sm leading-tight">
                            {{ $item->details->first()?->asset?->name ?? 'Aset Tidak Ditemukan' }}
                            @if($item->details->count() > 1)
                                <span class="text-[10px] font-bold text-brand inline-block ml-1 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-100">
                                    +{{ $item->details->count() - 1 }} Item
                                </span>
                            @endif
                        </h4>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $item->details->first()?->asset?->room?->name ?? '-' }}</p>
                    </div>
                </div>
                
                <!-- Badge Status -->
                @if($item->status == 'completed')
                    <span class="bg-green-50 text-green-600 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border border-green-100">Selesai</span>
                @elseif($item->status == 'active' || $item->status == 'pending' || $item->status == 'approved')
                    <span class="bg-blue-50 text-brand px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border border-blue-100">Aktif</span>
                @elseif($item->status == 'rejected')
                    <span class="bg-red-50 text-red-500 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border border-red-100">Ditolak</span>
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
                        {{ $item->actual_return_time ? \Carbon\Carbon::parse($item->actual_return_time)->format('d M Y, H:i') : '-' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- MODAL DETAIL RIWAYAT -->
        <div id="modal-{{ $item->id }}" class="fixed inset-0 z-50 hidden flex items-end justify-center p-0 sm:p-4 sm:items-center">
            <!-- Backdrop Gelap -->
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity opacity-0" id="backdrop-{{ $item->id }}" onclick="closeModal('modal-{{ $item->id }}')"></div>
            
            <!-- Konten Modal -->
            <div class="bg-white w-full sm:max-w-md rounded-t-[2rem] sm:rounded-[2rem] p-6 relative z-10 transform translate-y-full transition-transform duration-300 shadow-2xl" id="content-{{ $item->id }}">
                
                <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-6 sm:hidden"></div>

                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="font-extrabold text-slate-800 text-xl tracking-tight">Detail Riwayat</h3>
                        <p class="text-xs font-medium text-slate-500 mt-1">ID Transaksi: #{{ $item->id }}</p>
                    </div>
                    <button onclick="closeModal('modal-{{ $item->id }}')" class="w-8 h-8 bg-slate-100 text-slate-500 rounded-full flex items-center justify-center hover:bg-slate-200 active:scale-90 transition-all">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                
                <!-- Rincian Keranjang Barang -->
                <div class="space-y-3 mb-6 max-h-[35vh] overflow-y-auto pr-1">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Fasilitas yang dipinjam</p>
                    @foreach($item->details as $detail)
                    <div class="flex items-center gap-3 bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                        <div class="flex-1">
                            <p class="text-[10px] font-bold text-brand uppercase tracking-widest">{{ $detail->asset->category->name ?? 'Aset' }}</p>
                            <h4 class="text-sm font-bold text-slate-800 mt-0.5">{{ $detail->asset->name }}</h4>
                        </div>
                        <div class="bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm">
                            <span class="text-xs font-extrabold text-slate-800">{{ $detail->quantity }}x</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Info Alasan -->
                <div class="bg-[#F2F5F9] rounded-2xl p-4 mb-4">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tujuan Peminjaman</p>
                    <p class="text-xs font-bold text-slate-700">{{ $item->reason ?? 'Tidak ada keterangan.' }}</p>
                </div>

                <!-- Info Catatan Admin (Kalau ada) -->
                @if($item->admin_note)
                <div class="bg-orange-50 border border-orange-100 rounded-2xl p-4">
                    <p class="text-[10px] font-bold text-orange-400 uppercase tracking-widest mb-1">Catatan Admin</p>
                    <p class="text-xs font-bold text-orange-700">{{ $item->admin_note }}</p>
                </div>
                @endif
                
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

<!-- SCRIPT UNTUK ANIMASI MODAL -->
<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        const backdrop = document.getElementById(id.replace('modal-', 'backdrop-'));
        const content = document.getElementById(id.replace('modal-', 'content-'));
        
        modal.classList.remove('hidden');
        // Trigger render
        void modal.offsetWidth; 
        
        backdrop.classList.remove('opacity-0');
        content.classList.remove('translate-y-full');
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        const backdrop = document.getElementById(id.replace('modal-', 'backdrop-'));
        const content = document.getElementById(id.replace('modal-', 'content-'));
        
        backdrop.classList.add('opacity-0');
        content.classList.add('translate-y-full');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection