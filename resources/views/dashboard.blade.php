@extends('layouts.app')

@section('content')
<!-- MASUKIN SCRIPT FULLCALENDAR -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

<div class="px-5 py-6 space-y-8 pb-12">
    
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

    <!-- Alert Denda -->
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

    <!-- ALERT SUKSES -->
    @if(session('success'))
    <div id="success-alert" class="mx-5 mt-4 mb-2 bg-green-50 border border-green-200 p-4 rounded-2xl flex items-start gap-3 shadow-sm shadow-green-900/5 transition-all duration-300">
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

    <!-- Aksi Cepat Section -->
    <section>
        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 px-1">Aksi Cepat</h3>
        <div class="grid grid-cols-4 gap-4">
            <!-- Menu Asset -->
             <a href="{{ route('peminjaman.assets') }}" class="flex flex-col items-center gap-2 group">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm border border-slate-50 group-active:scale-90 transition-transform bg-white text-brand">
                    <i class="fa-solid fa-box-open text-xl"></i>
                </div>
                <span class="text-[10px] font-extrabold text-slate-500">Asset</span>
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

    <!-- BARU: TOMBOL UNTUK BUKA MODAL KALENDER -->
    <section class="space-y-4">
        <div class="flex items-center px-1">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Informasi Jadwal</h3>
        </div>
        
        <button type="button" onclick="openCalendarModal()" class="w-full bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm flex items-center justify-between group active:scale-95 transition-transform">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 text-brand rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-regular fa-calendar-days text-xl"></i>
                </div>
                <div class="text-left">
                    <h4 class="text-sm font-black text-slate-800 leading-tight">Cek Kalender Booking</h4>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Lihat ketersediaan ruangan lab</p>
                </div>
            </div>
            <i class="fa-solid fa-chevron-right text-slate-300 text-sm group-hover:translate-x-1 transition-transform"></i>
        </button>
    </section>

    <!-- Pinjaman Aktif Section -->
    <section class="space-y-4">
        <div class="flex items-center justify-between px-1">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Pinjaman Aktif</h3>
            <span class="text-[10px] font-bold text-brand bg-blue-50 px-3 py-1 rounded-lg border border-blue-100">{{ count($active_loans) }} Item</span>
        </div>

        <div class="space-y-3">
            @forelse($active_loans as $loan)
            
            <div onclick="openModal('modal-{{ $loan->id }}')" class="bg-white p-4 rounded-3xl border border-slate-100 flex items-center justify-between shadow-sm cursor-pointer active:scale-95 transition-transform group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-blue-50 text-brand rounded-xl flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-box"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">
                            {{ $loan->details->first()?->asset?->category?->name ?? 'Aset Lab' }}
                        </p>
                        <h4 class="text-sm font-bold text-slate-800">
                            {{ $loan->details->first()?->asset?->name ?? 'Menunggu Data...' }}
                            @if($loan->details->count() > 1)
                                <span class="text-[10px] font-bold text-brand inline-block ml-1 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-100">
                                    +{{ $loan->details->count() - 1 }} Item
                                </span>
                            @endif
                        </h4>
                        
                        <div class="flex items-center gap-2 mt-1.5">
                            <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-100 px-2 py-1 rounded-lg">
                                <i class="fa-regular fa-clock text-[10px] text-brand"></i>
                                <span class="text-[10px] font-bold text-slate-500">
                                    {{ \Carbon\Carbon::parse($loan->start_time)->format('d M H:i') }} - {{ \Carbon\Carbon::parse($loan->end_time)->format('H:i') }}
                                </span>
                            </div>
                            
                            @if($loan->status == 'pending')
                                <span class="text-[9px] font-black text-orange-500 bg-orange-50 px-2 py-1 rounded-lg uppercase">Menunggu</span>
                            @elseif(\Carbon\Carbon::parse($loan->start_time)->isFuture())
                                <span class="text-[9px] font-black text-blue-500 bg-blue-50 px-2 py-1 rounded-lg uppercase">Booking</span>
                            @endif
                        </div>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-slate-300 text-xs"></i>
            </div>

            <!-- MODAL DETAIL -->
            <div id="modal-{{ $loan->id }}" class="fixed inset-0 z-[60] hidden flex items-end justify-center p-0 sm:p-4 sm:items-center">
                <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity opacity-0" id="backdrop-{{ $loan->id }}" onclick="closeModal('modal-{{ $loan->id }}')"></div>
                <div class="bg-white w-full sm:max-w-md rounded-t-[2rem] sm:rounded-[2rem] p-6 relative z-10 transform translate-y-full transition-transform duration-300 shadow-2xl" id="content-{{ $loan->id }}">
                    <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-6 sm:hidden"></div>
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="font-extrabold text-slate-800 text-xl tracking-tight">Detail Pinjaman</h3>
                            <p class="text-xs font-medium text-slate-500 mt-1">ID Transaksi: #{{ $loan->id }}</p>
                        </div>
                        <button onclick="closeModal('modal-{{ $loan->id }}')" class="w-8 h-8 bg-slate-100 text-slate-500 rounded-full flex items-center justify-center hover:bg-slate-200 active:scale-90 transition-all shrink-0">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    
                    <div class="space-y-3 mb-6 max-h-[35vh] overflow-y-auto pr-1">
                        @foreach($loan->details as $detail)
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

                    <form action="{{ route('peminjaman.selesai', $loan->id) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menyelesaikan pinjaman ini ?')">
                        @csrf
                        <button type="submit" class="w-full bg-brand text-white font-extrabold text-sm py-4 rounded-2xl flex items-center justify-center gap-2 hover:bg-blue-900 active:scale-95 transition-all shadow-lg shadow-blue-900/20">
                            <i class="fa-regular fa-circle-check text-lg"></i>
                            Selesai Pinjam
                        </button>
                    </form>
                </div>
            </div>

            @empty
            <div class="bg-white py-12 rounded-[2.5rem] border border-dashed border-slate-200 text-center flex flex-col items-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                    <i class="fa-solid fa-inbox text-2xl text-slate-300"></i>
                </div>
                <h4 class="text-sm font-black text-slate-800">Tidak Ada Pinjaman Aktif</h4>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Belum ada fasilitas yang dipinjam.</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- Peraturan Peminjaman -->
    <section class="space-y-4">
        <div class="flex items-center px-1">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Informasi Penting</h3>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-black text-slate-800 leading-tight">Peraturan Umum</h4>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Fasilitas FIR Paramadina</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0 mt-0.5"><i class="fa-regular fa-calendar-xmark text-[10px] text-slate-500"></i></div>
                    <p class="text-xs font-medium text-slate-600 leading-relaxed"><span class="font-bold text-slate-800">Peminjaman ruangan tidak dapat dilakukan</span> di hari minggu dan libur nasional.</p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0 mt-0.5"><i class="fa-solid fa-file-signature text-[10px] text-slate-500"></i></div>
                    <p class="text-xs font-medium text-slate-600 leading-relaxed">1 formulir pengajuan ruangan <span class="font-bold text-slate-800">hanya berlaku untuk 1 mahasiswa.</span></p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-red-50 border border-red-100 flex items-center justify-center shrink-0 mt-0.5"><i class="fa-solid fa-stopwatch text-[10px] text-red-500"></i></div>
                    <p class="text-xs font-medium text-slate-600 leading-relaxed">Apabila mahasiswa <span class="font-bold text-red-500">tidak hadir dalam waktu 20 menit</span> sejak jadwal diajukan, maka pengajuan batal.</p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0 mt-0.5"><i class="fa-regular fa-clock text-[10px] text-slate-500"></i></div>
                    <p class="text-xs font-medium text-slate-600 leading-relaxed">Peminjaman ruangan <span class="font-bold text-slate-800">maksimal hingga pukul 19.00.</span></p>
                </div>
            </div>
        </div>
    </section>

</div>

<!-- MODAL KALENDER (Ngapung di Atas) -->
<div id="calendarModal" class="fixed inset-0 z-[100] hidden flex items-end justify-center p-0 sm:p-4 sm:items-center">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0" id="backdrop-calendar" onclick="closeCalendarModal()"></div>
    
    <div class="bg-white w-full sm:max-w-xl rounded-t-[2.5rem] sm:rounded-[2.5rem] p-6 relative z-10 transform translate-y-full transition-transform duration-300 shadow-2xl" id="content-calendar">
        <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-6 sm:hidden"></div>
        
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="font-extrabold text-slate-800 text-xl tracking-tight">Jadwal Ruangan</h3>
                <div class="flex gap-3 mt-1.5 text-[9px] font-bold text-slate-500 uppercase tracking-widest">
                    <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Disetujui</div>
                    <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Menunggu</div>
                </div>
            </div>
            <button onclick="closeCalendarModal()" class="w-8 h-8 bg-slate-100 text-slate-500 rounded-full flex items-center justify-center hover:bg-slate-200 active:scale-90 transition-all shrink-0">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        
        <!-- Wadah Kalender -->
        <div class="min-h-[400px]">
            <div id="calendar" class="text-xs"></div>
        </div>
    </div>
</div>

<!-- CSS KHUSUS KALENDER -->
<style>
    .fc { font-family: inherit; }
    .fc-toolbar-title { font-size: 14px !important; font-weight: 900 !important; color: #1e293b; }
    .fc-button-primary { background-color: #f8fafc !important; color: #64748b !important; border: 1px solid #f1f5f9 !important; border-radius: 10px !important; text-transform: capitalize !important; font-weight: 800 !important; font-size: 10px !important; padding: 4px 8px !important; box-shadow: none !important;}
    .fc-button-active { background-color: #1e3a8a !important; color: white !important; }
    .fc-col-header-cell-cushion { font-size: 10px; text-transform: uppercase; color: #94a3b8; padding-top: 8px !important; padding-bottom: 8px !important; }
    .fc-daygrid-day-number { font-size: 12px; font-weight: 700; color: #334155; }
    .fc-event { border: none !important; border-radius: 4px !important; padding: 2px 4px !important; font-size: 9px !important; font-weight: 700 !important; margin-bottom: 2px !important; }
    .fc-theme-standard th, .fc-theme-standard td { border-color: #f1f5f9; }
    .fc-day-today { background-color: #eff6ff !important; }
</style>

<!-- SCRIPT MODAL & KALENDER -->
<script>
    let calendar; // Siapin variabel global

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'title',
                right: 'prev,next'
            },
            height: 400,
            events: {!! $calendar_events !!}, 
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                meridiem: false,
                hour12: false
            }
        });
        // Sengaja nggak di-render di sini biar gak rusak bentuknya
    });

    // Buka Modal Kalender
    function openCalendarModal() {
        const modal = document.getElementById('calendarModal');
        const backdrop = document.getElementById('backdrop-calendar');
        const content = document.getElementById('content-calendar');
        
        modal.classList.remove('hidden');
        void modal.offsetWidth; 
        backdrop.classList.remove('opacity-0');
        content.classList.remove('translate-y-full');

        // Render kalender SETELAH modal kebuka 
        setTimeout(() => {
            calendar.render();
            calendar.updateSize(); // Fix kalender kepotong/gepeng
        }, 300); // 300ms sesuai durasi animasi modal CSS lo
    }

    // Tutup Modal Kalender
    function closeCalendarModal() {
        const modal = document.getElementById('calendarModal');
        const backdrop = document.getElementById('backdrop-calendar');
        const content = document.getElementById('content-calendar');
        
        backdrop.classList.add('opacity-0');
        content.classList.add('translate-y-full');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Modal Peminjaman Aktif (Bawaan)
    function openModal(id) {
        const modal = document.getElementById(id);
        const backdrop = document.getElementById(id.replace('modal-', 'backdrop-'));
        const content = document.getElementById(id.replace('modal-', 'content-'));
        
        modal.classList.remove('hidden');
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