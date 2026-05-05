@extends('layouts.petugas')

@section('content')
<div class="pb-10">
    
    <!-- GREETING BANNER (Warna disamakan dengan Admin) -->
    <div class="relative bg-blue-900 rounded-[2.5rem] p-8 mb-8 overflow-hidden shadow-2xl shadow-blue-900/20">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-blue-500 opacity-20 rounded-full blur-2xl translate-y-1/3 -translate-x-1/4"></div>
        
        <div class="relative z-10 text-white">
            <p class="text-blue-300 text-sm font-bold tracking-widest uppercase mb-1">
                Panel Operasional
            </p>
            <h1 class="text-3xl font-black leading-tight">
                Halo {{ Auth::user()->name }}! 🚀
            </h1>
            <p class="text-blue-100 text-sm mt-2 font-medium max-w-xl leading-relaxed">
                Pantau pergerakan aset SAPA Paramadina, amati aktivitas peminjaman, dan segera laporkan jika terjadi kerusakan barang.
            </p>
        </div>
    </div>

    <!-- STATISTIC CARDS GRID (Aksen Biru & Orange untuk Warning) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Card 1: Antrean Pending (Tetap Orange biar Admin & Petugas sama-sama aware) -->
        <div class="bg-gradient-to-br from-amber-400 to-orange-500 p-6 rounded-[2rem] shadow-lg shadow-orange-500/20 text-white relative overflow-hidden">
            <div class="relative z-10 flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-orange-100 mb-1">Butuh Review</p>
                    <h3 class="text-4xl font-black">{{ $pending_count }}</h3>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fa-solid fa-hourglass-half text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <span class="text-xs font-bold text-orange-50 italic">Menunggu ACC Admin</span>
            </div>
        </div>

        <!-- Card 2: Aktif (Biru Admin) -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 group hover:shadow-md transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Sedang Dipinjam</p>
                    <h3 class="text-3xl font-black text-blue-900">{{ $active_count }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-900 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-people-carry-box text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-50">
                <span class="text-xs font-bold text-slate-500">Mahasiswa sedang di Lab</span>
            </div>
        </div>

        <!-- Card 3: Telat (Merah Denda) -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Melebihi Batas</p>
                    <h3 class="text-3xl font-black text-red-600">{{ $late_count }}</h3>
                </div>
                <div class="w-12 h-12 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-50">
                <span class="text-xs font-bold text-slate-500 italic">Butuh penagihan</span>
            </div>
        </div>

        <!-- Card 4: Selesai Hari Ini (Biru Muda/Cyan) -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Selesai Hari Ini</p>
                    <h3 class="text-3xl font-black text-blue-600">{{ $today_completed }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-50">
                <span class="text-xs font-bold text-slate-500">Aset kembali aman</span>
            </div>
        </div>

    </div>

    <!-- ACTION SECTION: TABEL PEMANTAUAN -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-black text-slate-800">Daftar Pantauan Lapangan</h3>
                <p class="text-xs font-medium text-slate-500 mt-0.5">Pantau dan laporkan jika terjadi kendala pada aset.</p>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="p-4 pl-6 text-[10px] uppercase text-slate-400 font-black tracking-widest">Peminjam</th>
                        <th class="p-4 text-[10px] uppercase text-slate-400 font-black tracking-widest">Aset & Ruangan</th>
                        <th class="p-4 text-[10px] uppercase text-slate-400 font-black tracking-widest">Status</th>
                        <th class="p-4 pr-6 text-[10px] uppercase text-slate-400 font-black tracking-widest text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($loans as $loan)
                    <tr class="hover:bg-slate-50 transition group">
                        <td class="p-4 pl-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold text-sm shrink-0">
                                    {{ substr($loan->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $loan->user->name }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $loan->user->nim ?? 'NIM -' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <p class="text-sm font-bold text-slate-800">{{ $loan->details->first()?->asset?->name ?? 'Aset' }}</p>
                            <p class="text-[10px] font-bold text-blue-700 bg-blue-50 inline-block px-2 py-0.5 rounded-md mt-1 border border-blue-100">
                                <i class="fa-solid fa-location-dot mr-1"></i> {{ $loan->details->first()?->asset?->room?->name ?? 'Tanpa Ruangan' }}
                            </p>
                        </td>
                        <td class="p-4">
                            @if($loan->status == 'pending')
                                <span class="bg-orange-50 text-orange-600 border border-orange-100 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">Pending</span>
                            @elseif($loan->status == 'approved' || $loan->status == 'active')
                                <span class="bg-blue-50 text-blue-900 border border-blue-100 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">Aktif</span>
                            @elseif($loan->status == 'late')
                                <span class="bg-red-50 text-red-600 border border-red-100 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">Terlambat</span>
                            @endif
                        </td>
                        <td class="p-4 pr-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" class="w-8 h-8 rounded-full bg-white border border-slate-200 text-slate-400 hover:text-blue-900 hover:border-blue-900 flex items-center justify-center transition-all shadow-sm">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </button>
                                
                                <button type="button" onclick="openModal('reportModal-{{ $loan->id }}')" class="bg-red-50 hover:bg-red-600 text-red-600 hover:text-white border border-red-100 hover:border-red-600 px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm active:scale-95 flex items-center gap-1">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Lapor
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-10 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-200">
                                <i class="fa-solid fa-calendar-check text-2xl"></i>
                            </div>
                            <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest">Belum Ada Aktivitas</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1 italic">Semua data peminjaman kosong.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= MODALS SECTION (TETAP SAMA) ================= -->
@foreach($loans as $loan)
<div id="reportModal-{{ $loan->id }}" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-blue-900/50 backdrop-blur-sm transition-opacity opacity-0" id="backdrop-reportModal-{{ $loan->id }}" onclick="closeModal('reportModal-{{ $loan->id }}')"></div>
    
    <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-8 relative z-10 transform scale-95 opacity-0 transition-all duration-300 shadow-2xl shadow-blue-900/10" id="content-reportModal-{{ $loan->id }}">
        <div class="flex justify-between items-start mb-6 text-blue-900">
            <div>
                <h3 class="text-xl font-black italic">LAPOR KERUSAKAN</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">ID Peminjaman: #{{ $loan->id }}</p>
            </div>
            <button type="button" onclick="closeModal('reportModal-{{ $loan->id }}')" class="text-slate-300 hover:text-red-500 transition-colors">
                <i class="fa-solid fa-circle-xmark text-2xl"></i>
            </button>
        </div>

        <form action="{{ route('petugas.report.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Aset Bermasalah</label>
                    <select name="asset_id" class="w-full px-4 py-3 rounded-2xl border border-slate-100 bg-slate-50 text-sm font-bold text-slate-800 outline-none focus:ring-2 focus:ring-blue-900 transition" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($loan->details as $detail)
                            @if($detail->asset)
                                <option value="{{ $detail->asset->id }}">{{ $detail->asset->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Deskripsi Detail</label>
                    <textarea name="deskripsi_kerusakan" rows="3" class="w-full px-4 py-3 rounded-2xl border border-slate-100 bg-slate-50 text-sm font-bold text-slate-800 outline-none focus:ring-2 focus:ring-blue-900 transition" placeholder="Jelaskan kondisi kerusakan..." required></textarea>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Upload Bukti Foto</label>
                    <input type="file" name="foto_kerusakan" accept="image/*" class="w-full px-4 py-2 rounded-2xl border border-dashed border-slate-200 text-xs font-bold text-slate-400 file:bg-blue-900 file:text-white file:rounded-xl file:border-none file:px-4 file:py-2 file:mr-4 file:cursor-pointer">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-blue-900 text-white font-black py-4 rounded-2xl hover:bg-blue-800 transition shadow-lg shadow-blue-900/20 uppercase tracking-widest text-xs">
                        Kirim Laporan Kerusakan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        const backdrop = document.getElementById('backdrop-' + id);
        const content = document.getElementById('content-' + id);
        
        modal.classList.remove('hidden');
        void modal.offsetWidth;
        
        backdrop.classList.remove('opacity-0');
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        const backdrop = document.getElementById('backdrop-' + id);
        const content = document.getElementById('content-' + id);
        
        backdrop.classList.add('opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection