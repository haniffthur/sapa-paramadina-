<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petugas Dashboard - SAPA Paramadina</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">
        <!-- SIDEBAR PETUGAS (SAMA PERSIS DENGAN ADMIN) -->
        <aside class="w-64 bg-blue-900 text-white flex-shrink-0 hidden md:flex flex-col">
            <div class="p-6">
                <h1 class="text-2xl font-bold tracking-tighter">SAPA <span class="text-blue-300">PETUGAS</span></h1>
                <p class="text-xs text-blue-400">Fakultas Ilmu Rekayasa</p>
            </div>

            <nav class="flex-1 px-4 space-y-2 overflow-y-auto">
                <!-- Dashboard -->
                <a href="{{ route('petugas.dashboard') }}" 
                   class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('petugas.dashboard') ? 'bg-blue-800 text-white shadow-md' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fa-solid fa-chart-line w-5"></i> 
                    <span class="text-sm font-semibold">Dashboard</span>
                </a>
                
                <div class="pt-4 pb-2 text-[10px] font-bold text-blue-400 uppercase px-3 tracking-widest">Pemantauan</div>
                
                <!-- Pantau Pinjaman -->
             <a href="{{ route('petugas.peminjaman.index') }}" 
   class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('petugas.peminjaman.*') ? 'bg-blue-800 text-white shadow-md' : 'text-blue-100 hover:bg-blue-800' }}">
    <i class="fa-solid fa-binoculars w-5"></i> 
    <span class="text-sm font-semibold">Pantau Pinjaman</span>
</a>

                <div class="pt-4 pb-2 text-[10px] font-bold text-blue-400 uppercase px-3 tracking-widest">Laporan Teknis</div>

                <!-- Laporan Kerusakan -->
                <a href="{{ route('petugas.reports.index') }}" 
                   class="flex items-center gap-3 p-3 rounded-lg transition {{ request()->routeIs('petugas.reports.*') ? 'bg-blue-800 text-white shadow-md' : 'text-blue-100 hover:bg-blue-800' }}">
                    <i class="fa-solid fa-screwdriver-wrench w-5"></i> 
                    <span class="text-sm font-semibold">Lapor Kerusakan</span>
                </a>
            </nav>

            <!-- Logout Area -->
            <div class="p-4 border-t border-blue-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="flex items-center gap-3 p-3 w-full text-left rounded-lg hover:bg-red-600 transition">
                        <i class="fa-solid fa-right-from-bracket w-5"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 flex flex-col min-w-0">
            <!-- Header (Identik 100% dengan Admin) -->
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

            <!-- Konten Dinamis -->
            <div class="p-8">
                <!-- Alert Success -->
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm rounded-r-lg" role="alert">
                        <p class="font-bold">Berhasil!</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Alert Error -->
                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-sm rounded-r-lg" role="alert">
                        <p class="font-bold">Waduh!</p>
                        <p class="text-sm">{{ session('error') }}</p>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>