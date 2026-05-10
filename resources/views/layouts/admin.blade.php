<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SAPA Paramadina</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        <aside class="w-64 bg-blue-900 text-white flex-shrink-0 hidden md:flex flex-col">
            <div class="p-6">
                <h1 class="text-2xl font-bold tracking-tighter">SAPA <span class="text-blue-300">ADMIN</span></h1>
                <p class="text-xs text-blue-400">Fakultas Ilmu Rekayasa</p>
            </div>

            <nav class="flex-1 px-4 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-800 text-white shadow-md' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fa-solid fa-chart-line w-5"></i> 
                    <span class="text-sm font-semibold">Dashboard</span>
                </a>
                
                <div class="pt-4 pb-2 text-[10px] font-bold text-blue-400 uppercase px-3 tracking-widest">Manajemen Fasilitas</div>
                
                <a href="{{ route('admin.rooms.index') }}" 
                   class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('admin.rooms.*') ? 'bg-blue-800 text-white shadow-md' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fa-solid fa-door-open w-5"></i> 
                    <span class="text-sm font-semibold">Daftar Ruangan</span>
                </a>

                <a href="{{ route('admin.assets.index') }}" 
                   class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('admin.assets.*') ? 'bg-blue-800 text-white shadow-md' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fa-solid fa-boxes-stacked w-5"></i> 
                    <span class="text-sm font-semibold">Daftar Aset</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" 
                   class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('admin.categories.*') ? 'bg-blue-800 text-white shadow-md' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fa-solid fa-tags w-5"></i> 
                    <span class="text-sm font-semibold">Kategori</span>
                </a>

                <div class="pt-4 pb-2 text-[10px] font-bold text-blue-400 uppercase px-3 tracking-widest">Peminjaman</div>

                <a href="{{ route('admin.peminjaman.index') }}" 
                   class="flex items-center justify-between p-3 rounded-lg transition {{ request()->routeIs('admin.peminjaman.*') ? 'bg-blue-800 text-white shadow-md' : 'text-blue-100 hover:bg-blue-800' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-clipboard-check w-5"></i> 
                        <span class="text-sm font-semibold">Approval</span>
                    </div>
                    <span id="loan-count-badge" class="{{ (isset($pendingCount) && $pendingCount > 0) ? '' : 'hidden' }} bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                        {{ $pendingCount ?? 0 }}
                    </span>
                </a>

                <a href="{{ route('admin.penalties.index') }}" 
                   class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('admin.penalties.*') ? 'bg-blue-800 text-white shadow-md' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fa-solid fa-circle-exclamation w-5"></i> 
                    <span class="text-sm font-semibold">Denda & Penalti</span>
                </a>

                <a href="{{ route('admin.report') }}" 
                   class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('admin.report') ? 'bg-blue-800 text-white shadow-md' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fa-solid fa-file-invoice w-5"></i> 
                    <span class="text-sm font-semibold">Riwayat Peminjaman</span>
                </a>
                
                <a href="{{ route('admin.asset-reports.index') }}" 
                   class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('admin.asset-reports.*') ? 'bg-blue-800 text-white shadow-md' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fa-solid fa-screwdriver-wrench w-5 text-center"></i> 
                    <span class="text-sm font-semibold">Laporan Kerusakan</span>
                </a>

                <div class="pt-4 pb-2 text-[10px] font-bold text-blue-400 uppercase px-3 tracking-widest">Sistem</div>
                
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('admin.users.*') ? 'bg-blue-800 text-white shadow-md' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fa-solid fa-users w-5"></i> 
                    <span class="text-sm font-semibold">Pengguna</span>
                </a>

                <a href="{{ route('admin.prodis.index') }}" 
                   class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('admin.prodis.*') ? 'bg-blue-800 text-white shadow-md' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fa-solid fa-graduation-cap w-5"></i> 
                    <span class="text-sm font-semibold">Program Studi</span>
                </a>

                <a href="{{ route('admin.settings.index') }}" 
   class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('admin.settings.*') ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-800' }}">
    <i class="fa-solid fa-gear w-5"></i>
    <span class="text-sm font-semibold">Settings</span>
</a>
                
            </nav>

            <div class="p-4 border-t border-blue-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="flex items-center gap-3 p-3 w-full text-left rounded-lg hover:bg-red-600 transition">
                        <i class="fa-solid fa-right-from-bracket w-5"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 flex flex-col min-w-0">
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8">
                <div class="text-gray-600 md:hidden">
                    <i class="fa-solid fa-bars text-xl"></i>
                </div>
                <div class="ml-auto flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-gray-800 leading-none">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest">{{ Auth::user()->role }}</p>
                    </div>
                    <img src="{{ Auth::user()->avatar }}" class="w-10 h-10 rounded-xl border-2 border-white shadow-sm object-cover">
                </div>
            </header>

            <div class="p-8">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm rounded-r-lg">
                        <p class="font-bold">Berhasil!</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
 <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    console.log("ADMIN REALTIME LOADED");

    Pusher.logToConsole = false;

    const pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
        cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
        forceTLS: true,
        enabledTransports: ['ws', 'wss']
    });

    // CONNECTED
    pusher.connection.bind('connected', function () {
        console.log('ADMIN PUSHER CONNECTED');
    });

    // SUBSCRIBE CHANNEL ADMIN
    const channel = pusher.subscribe('admin-notification');

    channel.bind('pusher:subscription_succeeded', function () {
        console.log('ADMIN CHANNEL SUBSCRIBED');
    });

    // EVENT PEMINJAMAN BARU
    channel.bind('loan-submitted', function(data) {

        console.log('PEMINJAMAN BARU:', data);

        // UPDATE BADGE
        const badge = document.getElementById('loan-count-badge');

        if (badge) {
            badge.classList.remove('hidden');

            let currentCount = parseInt(badge.innerText) || 0;

            badge.innerText = currentCount + 1;

            badge.classList.add('animate-bounce');

            setTimeout(() => {
                badge.classList.remove('animate-bounce');
            }, 1000);
        }

        // TOAST NOTIFIKASI
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'info',
            title: 'Peminjaman Baru!',
            text: data.message,
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true
        });

        // AUTO REFRESH HALAMAN APPROVAL
        if (window.location.pathname.includes('peminjaman')) {

            setTimeout(() => {
                window.location.reload();
            }, 2000);

        }

    });

});
</script>
</body>
</html>