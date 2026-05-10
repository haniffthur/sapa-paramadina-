@extends('layouts.petugas')

@section('content')
<div class="px-5 py-6 space-y-8 pb-12">
    
    <div class="relative rounded-[2.5rem] overflow-hidden aspect-[16/9] shadow-2xl shadow-blue-900/10 border-4 border-white">
        <img src="{{ asset('img/univ.jpg') }}" class="w-full h-full object-cover grayscale-[0.2] brightness-75" onerror="this.src='https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=1000&auto=format&fit=crop'">
        <div class="absolute inset-0 bg-gradient-to-t from-blue-900/95 via-blue-900/40 to-transparent"></div>
        
        <div class="absolute bottom-6 left-6 right-6">
            <p class="text-[10px] font-bold text-blue-200 uppercase tracking-[0.3em] mb-1">Petugas Operasional</p>
            <h1 class="text-2xl font-black text-white leading-tight">Pantau Aset<br>Lebih Cepat.</h1>
        </div>
    </div>

    @if(session('success'))
    <div id="success-alert" class="bg-green-50 border border-green-200 p-4 rounded-2xl flex items-start gap-3 shadow-sm shadow-green-900/5 transition-all duration-300">
        <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center shrink-0">
            <i class="fa-solid fa-check"></i>
        </div>
        <div class="flex-1">
            <h4 class="text-sm font-bold text-slate-800">Yay, Berhasil!</h4>
            <p class="text-xs font-medium text-slate-500 mt-0.5">{{ session('success') }}</p>
        </div>
        <button onclick="document.getElementById('success-alert').style.display='none'" class="text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    <section>
        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 px-1">Status Hari Ini</h3>
        <div class="grid grid-cols-4 gap-4">
            <div class="flex flex-col items-center gap-2 group">
                <div class="w-14 h-14 rounded-[1.2rem] flex items-center justify-center shadow-sm border border-slate-50 bg-white text-orange-500">
                    <span class="text-xl font-black">{{ $pending_count ?? 0 }}</span>
                </div>
                <span class="text-[10px] font-extrabold text-slate-500">Pending</span>
            </div>
            
            <div class="flex flex-col items-center gap-2 group">
                <div class="w-14 h-14 bg-white rounded-[1.2rem] flex items-center justify-center text-blue-600 shadow-sm border border-slate-100">
                    <span class="text-xl font-black">{{ $active_count ?? 0 }}</span>
                </div>
                <span class="text-[10px] font-bold text-slate-500">Aktif</span>
            </div>

            <div class="flex flex-col items-center gap-2 group">
                <div class="w-14 h-14 bg-white rounded-[1.2rem] flex items-center justify-center text-red-500 shadow-sm border border-slate-100 relative">
                    <span class="text-xl font-black">{{ $late_count ?? 0 }}</span>
                    @if(isset($late_count) && $late_count > 0)
                        <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 border-2 border-white rounded-full animate-pulse"></span>
                    @endif
                </div>
                <span class="text-[10px] font-bold text-slate-500">Telat</span>
            </div>

            <div class="flex flex-col items-center gap-2 group">
                <div class="w-14 h-14 bg-white rounded-[1.2rem] flex items-center justify-center text-green-500 shadow-sm border border-slate-100">
                    <span class="text-xl font-black">{{ $today_completed ?? 0 }}</span>
                </div>
                <span class="text-[10px] font-bold text-slate-500">Selesai</span>
            </div>
        </div>
    </section>

    <section class="space-y-4">
        <div class="flex items-center justify-between px-1">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Pantauan Terkini</h3>
            <a href="{{ route('petugas.peminjaman.index') }}" class="text-[10px] font-bold text-blue-900 bg-blue-50 px-3 py-1 rounded-lg border border-blue-100">Lihat Semua</a>
        </div>

        <div class="space-y-3">
            @forelse($loans as $loan)
            
            <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-10 h-10 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center shrink-0 border border-slate-100 font-black">
                        {{ substr($loan->user->name, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-blue-900 uppercase tracking-widest mb-0.5">
                            <i class="fa-solid fa-location-dot mr-1"></i> {{ $loan->details->first()?->asset?->room->name ?? 'LAB' }}
                        </p>
                        <h4 class="text-sm font-bold text-slate-800">
                            {{ $loan->user->name }}
                        </h4>
                        
                        <div class="flex items-center gap-2 mt-1.5">
                            <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-100 px-2 py-1 rounded-lg">
                                <i class="fa-solid fa-box-open text-[10px] text-slate-400"></i>
                                <span class="text-[10px] font-bold text-slate-500 truncate max-w-[100px]">
                                    {{ $loan->details->first()?->asset?->name ?? 'Aset' }}
                                </span>
                            </div>
                            
                            @if($loan->status == 'pending')
                                <span class="text-[9px] font-black text-orange-500 bg-orange-50 px-2 py-1 rounded-lg uppercase">Pending</span>
                            @elseif($loan->status == 'active' || $loan->status == 'approved')
                                <span class="text-[9px] font-black text-blue-500 bg-blue-50 px-2 py-1 rounded-lg uppercase">Aktif</span>
                            @elseif($loan->status == 'completed' || $loan->status == 'selesai')
                                <span class="text-[9px] font-black text-green-600 bg-green-50 px-2 py-1 rounded-lg uppercase">Selesai</span>
                            @elseif($loan->status == 'late')
                                <span class="text-[9px] font-black text-red-500 bg-red-50 px-2 py-1 rounded-lg uppercase">Telat</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex gap-2">
                    @if($loan->status == 'completed' || $loan->status == 'selesai')
                        <button type="button" onclick="openModal('reportModal-{{ $loan->id }}')" class="flex-1 bg-red-500 text-white py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-red-500/20 flex items-center justify-center gap-2 transition-all active:scale-95">
                            <i class="fa-solid fa-triangle-exclamation"></i> Lapor Kerusakan
                        </button>
                    @else
                        <div class="flex-1 bg-gray-50 text-gray-400 py-3.5 rounded-2xl text-[9px] font-bold uppercase tracking-widest flex items-center justify-center gap-2 border border-dashed border-gray-200">
                            <i class="fa-solid fa-clock-rotate-left text-[10px]"></i> Tunggu Selesai
                        </div>
                    @endif
                </div>
            </div>

            @empty
            <div class="bg-white py-12 rounded-[2.5rem] border border-dashed border-slate-200 text-center flex flex-col items-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3 border border-slate-100">
                    <i class="fa-solid fa-inbox text-2xl text-slate-300"></i>
                </div>
                <h4 class="text-sm font-black text-slate-800">Belum Ada Pantauan</h4>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Semua aset aman terkendali.</p>
            </div>
            @endforelse
        </div>
    </section>

</div>

@foreach($loans as $loan)
    @if($loan->status == 'completed' || $loan->status == 'selesai')
    <div id="reportModal-{{ $loan->id }}" class="fixed inset-0 z-[60] hidden flex items-end justify-center p-0 sm:p-4 sm:items-center">
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
                    <select name="asset_id" class="w-full px-4 py-3.5 rounded-xl border border-slate-100 bg-slate-50 focus:ring-2 focus:ring-blue-900 outline-none transition font-semibold text-sm text-slate-700">
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
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Deskripsi Kerusakan</label>
                    <textarea name="deskripsi_kerusakan" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-100 bg-slate-50 focus:ring-2 focus:ring-blue-900 outline-none transition font-semibold text-sm text-slate-700" placeholder="Jelaskan kondisi barang saat dipantau..."></textarea>
                </div>

                <div>
    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Ajuan Denda (Rp)</label>
    <div class="relative">
        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">Rp</span>
        <input type="number" name="nominal_denda" placeholder="Misal: 50000" class="w-full bg-slate-50 border border-slate-100 pl-10 pr-5 py-4 rounded-2xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-brand outline-none">
    </div>
    <p class="text-[9px] text-slate-400 font-bold mt-2 ml-1">*Kosongkan jika kerusakan wajar / tidak perlu denda.</p>
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