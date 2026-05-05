@extends('layouts.admin')

@section('content')
<div class="pb-10">
    
    <!-- 1. GREETING BANNER -->
    <div class="relative bg-blue-900 rounded-[2.5rem] p-8 mb-8 overflow-hidden shadow-2xl shadow-blue-900/20">
        <!-- Efek Cahaya / Dekorasi -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-blue-500 opacity-20 rounded-full blur-2xl translate-y-1/3 -translate-x-1/4"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <p class="text-blue-200 text-sm font-bold tracking-widest uppercase mb-1">
                    {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                </p>
                <h1 class="text-3xl font-black text-white leading-tight">
                    Selamat Datang, Admin! 👋
                </h1>
                <p class="text-blue-100 text-sm mt-2 font-medium max-w-xl leading-relaxed">
                    Pantau seluruh aktivitas peminjaman fasilitas dan kelola aset SAPA Paramadina dengan mudah dari kokpit ini.
                </p>
            </div>
            
            <!-- <div class="shrink-0 flex gap-3">
                <a href="{{ route('admin.rooms.create') }}" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 px-5 py-3 rounded-2xl font-bold text-sm backdrop-blur-md transition-all flex items-center gap-2 active:scale-95">
                    <i class="fa-solid fa-door-open"></i> + Ruangan
                </a>
            </div> -->
        </div>
    </div>

    <!-- 2. STATISTIC CARDS GRID (SEKARANG BISA DI-KLIK) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Card 1: Pengajuan Pending -->
        <!-- Ganti route('admin.peminjaman.index') sesuai dengan nama route lu kalau beda ya bro -->
        <a href="{{ route('admin.peminjaman.index') ?? '#' }}" class="block bg-gradient-to-br from-amber-400 to-orange-500 p-6 rounded-[2rem] shadow-lg shadow-orange-500/20 text-white relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute right-0 top-0 w-24 h-24 bg-white/10 rounded-bl-full transition-transform duration-500 group-hover:scale-125"></div>
            <div class="relative z-10 flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-orange-100 mb-1">Butuh Review</p>
                    <h3 class="text-4xl font-black">{{ $pending_loans_count }}</h3>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm group-hover:bg-white/30 transition-colors">
                    <i class="fa-solid fa-bell-concierge text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20 flex items-center justify-between">
                <span class="text-xs font-bold text-orange-50">Lihat Pengajuan</span>
                <i class="fa-solid fa-arrow-right text-orange-100 text-xs group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>

        <!-- Card 2: Total Aset -->
        <!-- Ganti route('admin.assets.index') sesuai dengan nama route lu kalau beda -->
        <a href="{{ route('admin.assets.index') ?? '#' }}" class="block bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 group hover:shadow-md hover:border-blue-200 hover:-translate-y-1 transition-all duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-blue-500 transition-colors mb-1">Total Aset</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $total_assets }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-boxes-stacked text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-50 flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 group-hover:text-blue-600 transition-colors">Kelola Barang</span>
                <i class="fa-solid fa-arrow-right text-slate-300 text-xs group-hover:translate-x-1 group-hover:text-blue-600 transition-all"></i>
            </div>
        </a>

        <!-- Card 3: Total Ruangan -->
        <a href="{{ route('admin.rooms.index') }}" class="block bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 group hover:shadow-md hover:border-emerald-200 hover:-translate-y-1 transition-all duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-emerald-500 transition-colors mb-1">Total Ruangan</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $total_rooms }}</h3>
                </div>
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-door-closed text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-50 flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 group-hover:text-emerald-600 transition-colors">Kelola Lab/Lokasi</span>
                <i class="fa-solid fa-arrow-right text-slate-300 text-xs group-hover:translate-x-1 group-hover:text-emerald-600 transition-all"></i>
            </div>
        </a>

        <!-- Card 4: Total Prodi -->
        <a href="{{ route('admin.prodis.index') }}" class="block bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 group hover:shadow-md hover:border-purple-200 hover:-translate-y-1 transition-all duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-purple-500 transition-colors mb-1">Total Prodi</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $total_prodis }}</h3>
                </div>
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-graduation-cap text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-50 flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 group-hover:text-purple-600 transition-colors">Kelola Program Studi</span>
                <i class="fa-solid fa-arrow-right text-slate-300 text-xs group-hover:translate-x-1 group-hover:text-purple-600 transition-all"></i>
            </div>
        </a>

    </div>

    <!-- 3. QUICK REVIEW SECTION (Tabel Transaksi Terbaru) -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-black text-slate-800">Aktivitas Peminjaman Terbaru</h3>
                <p class="text-xs font-medium text-slate-500 mt-0.5">Segera review pengajuan yang masih berstatus pending.</p>
            </div>
            <!-- Ganti href sesuai route halaman riwayat pinjaman lo -->
            <a href="{{ route('admin.peminjaman.index') ?? '#' }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-xl transition-all active:scale-95">
                Lihat Semua
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="p-4 pl-6 text-[10px] uppercase text-slate-400 font-black tracking-widest">Peminjam</th>
                        <th class="p-4 text-[10px] uppercase text-slate-400 font-black tracking-widest">Detail Aset</th>
                        <th class="p-4 text-[10px] uppercase text-slate-400 font-black tracking-widest">Waktu</th>
                        <th class="p-4 text-[10px] uppercase text-slate-400 font-black tracking-widest">Status</th>
                        <th class="p-4 pr-6 text-[10px] uppercase text-slate-400 font-black tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recent_loans as $loan)
                    <tr class="hover:bg-slate-50 transition cursor-pointer group">
                        <td class="p-4 pl-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold text-sm shrink-0 group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors">
                                    {{ substr($loan->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition-colors">{{ $loan->user->name }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase">{{ $loan->user->nim ?? 'Mahasiswa' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <p class="text-sm font-bold text-slate-800">{{ $loan->details->first()?->asset?->name ?? 'Aset' }}</p>
                            @if($loan->details->count() > 1)
                                <p class="text-[10px] font-bold text-brand bg-blue-50 inline-block px-2 py-0.5 rounded-md mt-1">
                                    +{{ $loan->details->count() - 1 }} Item Lain
                                </p>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-1.5 text-slate-600 text-xs font-bold">
                                <i class="fa-regular fa-clock text-slate-400"></i>
                                {{ \Carbon\Carbon::parse($loan->start_time)->format('d M, H:i') }}
                            </div>
                        </td>
                        <td class="p-4">
                            @if($loan->status == 'pending')
                                <span class="bg-orange-50 text-orange-600 border border-orange-100 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">Pending</span>
                            @elseif($loan->status == 'approved' || $loan->status == 'active')
                                <span class="bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">Aktif</span>
                            @elseif($loan->status == 'completed')
                                <span class="bg-green-50 text-green-600 border border-green-100 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">Selesai</span>
                            @else
                                <span class="bg-red-50 text-red-600 border border-red-100 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">{{ $loan->status }}</span>
                            @endif
                        </td>
                        <td class="p-4 pr-6 text-right">
                            <button class="w-8 h-8 rounded-full bg-white border border-slate-200 text-slate-400 group-hover:bg-brand group-hover:text-white group-hover:border-brand flex items-center justify-center ml-auto transition-all shadow-sm">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-inbox text-2xl text-slate-300"></i>
                            </div>
                            <h4 class="text-sm font-black text-slate-800">Belum Ada Aktivitas</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Data peminjaman kosong.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection