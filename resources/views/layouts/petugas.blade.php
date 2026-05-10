<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SAPA Petugas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        body { 
            font-family: 'Inter', sans-serif; 
            -webkit-tap-highlight-color: transparent;
            background-color: #f3f4f6; /* Warna abu-abu background layar luar */
        }
        .pb-safe { padding-bottom: calc(1rem + env(safe-area-inset-bottom)); }
    </style>
</head>
<body class="text-gray-800 antialiased flex justify-center">

    <div class="w-full max-w-md bg-gray-50 min-h-screen relative shadow-2xl sm:border-x sm:border-gray-200">
        
        <header class="sticky top-0 bg-white/90 backdrop-blur-md z-50 px-6 py-4 flex justify-between items-center border-b border-gray-100 rounded-b-[2rem] shadow-sm">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <img src="{{ Auth::user()->avatar ?? asset('default-avatar.png') }}" class="w-12 h-12 rounded-full border-2 border-blue-50 shadow-sm object-cover">
                    <div class="absolute bottom-0 right-0 bg-green-500 w-3.5 h-3.5 rounded-full border-2 border-white"></div>
                </div>
                <div>
                    <h1 class="text-sm font-bold text-gray-800 leading-tight">Halo, {{ explode(' ', Auth::user()->name)[0] }} 👋</h1>
                    <p class="text-[10px] text-blue-600 font-black uppercase tracking-widest mt-0.5">Petugas SAPA</p>
                </div>
            </div>
            
            <div class="relative w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 border border-gray-100 hover:bg-gray-100 transition cursor-pointer">
                <i class="fa-solid fa-bell"></i>
                <div id="petugas-notif-badge" class="hidden absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full border-2 border-white animate-bounce shadow-sm"></div>
            </div>
        </header>

        <main class="p-4 pb-32">
            @if(session('success'))
                <div class="bg-green-50 border border-green-100 text-green-600 p-4 mb-6 rounded-2xl text-xs font-bold shadow-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-lg"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-100 text-red-600 p-4 mb-6 rounded-2xl text-xs font-bold shadow-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-lg"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

        <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md bg-white border-t border-gray-100 rounded-t-[2.5rem] z-50 px-6 pt-4 pb-safe shadow-[0_-10px_40px_rgba(0,0,0,0.04)]">
            <div class="flex justify-between items-center">
                
                <a href="{{ route('petugas.dashboard') }}" class="flex flex-col items-center gap-1.5 p-2 w-16 group">
                    <div class="{{ request()->routeIs('petugas.dashboard') ? 'bg-blue-900 text-white shadow-md shadow-blue-200' : 'text-gray-400 bg-gray-50 group-hover:bg-gray-100' }} w-12 h-12 flex items-center justify-center rounded-[1rem] transition-all duration-300">
                        <i class="fa-solid fa-house text-lg"></i>
                    </div>
                    <span class="{{ request()->routeIs('petugas.dashboard') ? 'text-blue-900 font-black' : 'text-gray-400 font-bold' }} text-[9px] uppercase tracking-wider transition-colors">Home</span>
                </a>

                <a href="{{ route('petugas.peminjaman.index') }}" class="flex flex-col items-center gap-1.5 p-2 w-16 group">
                    <div class="{{ request()->routeIs('petugas.peminjaman.*') ? 'bg-blue-900 text-white shadow-md shadow-blue-200' : 'text-gray-400 bg-gray-50 group-hover:bg-gray-100' }} w-12 h-12 flex items-center justify-center rounded-[1rem] transition-all duration-300">
                        <i class="fa-solid fa-binoculars text-lg"></i>
                    </div>
                    <span class="{{ request()->routeIs('petugas.peminjaman.*') ? 'text-blue-900 font-black' : 'text-gray-400 font-bold' }} text-[9px] uppercase tracking-wider transition-colors">Pantau</span>
                </a>

                <a href="{{ route('petugas.reports.index') }}" class="flex flex-col items-center gap-1.5 p-2 w-16 group">
                    <div class="{{ request()->routeIs('petugas.reports.*') ? 'bg-blue-900 text-white shadow-md shadow-blue-200' : 'text-gray-400 bg-gray-50 group-hover:bg-gray-100' }} w-12 h-12 flex items-center justify-center rounded-[1rem] transition-all duration-300">
                        <i class="fa-solid fa-screwdriver-wrench text-lg"></i>
                    </div>
                    <span class="{{ request()->routeIs('petugas.reports.*') ? 'text-blue-900 font-black' : 'text-gray-400 font-bold' }} text-[9px] uppercase tracking-wider transition-colors">Lapor</span>
                </a>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="flex flex-col items-center gap-1.5 p-2 w-16 group">
                        <div class="text-red-400 bg-red-50 group-hover:bg-red-100 group-hover:text-red-600 w-12 h-12 flex items-center justify-center rounded-[1rem] transition-all duration-300">
                            <i class="fa-solid fa-right-from-bracket text-lg"></i>
                        </div>
                        <span class="text-red-400 text-[9px] font-bold uppercase tracking-wider transition-colors">Keluar</span>
                    </button>
                </form>

            </div>
        </nav>
    </div>

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Matikan log di console biar bersih, atau set true buat debug
        Pusher.logToConsole = false;

        const pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
            cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
            forceTLS: true
        });

        // Kita dengerin channel umum 'peminjaman-channel' 
        // karena petugas perlu tau aktivitas global (siapa pun mahasiswanya)
        const channel = pusher.subscribe('peminjaman-channel');

        // 1. Dengerin kalau ada Peminjaman Baru Masuk (Pending)
        channel.bind('peminjaman-submitted', function(data) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: 'Pengajuan Baru!',
                text: 'Ada mahasiswa yang baru saja mengajukan peminjaman.',
                showConfirmButton: false,
                timer: 3000
            });
            // Auto reload data tabel tanpa refresh full page jika memungkinkan, 
            // tapi paling aman pakai reload halaman dulu
            if(window.location.pathname.includes('petugas')) {
                window.location.reload();
            }
        });

        // 2. Dengerin kalau ada yang di-Approve Admin atau Selesai
        // Kita dengerin event-event yang sudah lo buat sebelumnya
        const events = ['.loan-approved', '.loan-completed', '.loan-rejected'];
        
        events.forEach(eventName => {
            channel.bind(eventName, function(data) {
                // Refresh halaman petugas biar status di card-nya berubah otomatis
                window.location.reload();
            });
        });
    });
</script>
</body>
</html>