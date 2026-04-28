<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>SAPA Paramadina</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#1e3a8a',
                        accent: '#3b82f6',
                        surface: '#F8FAFC',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        body { -webkit-tap-highlight-color: transparent; scroll-behavior: smooth; }
        .glass-header { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
        #bottom-nav { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .nav-hidden { transform: translateY(100%); opacity: 0; }
        .active-nav-item { color: #1e3a8a !important; }
        .active-nav-item i { transform: translateY(-2px); transition: transform 0.2s; }
        ::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-[#F2F5F9] antialiased text-slate-900 font-sans">

<header class="sticky top-0 z-50 glass-header border-b border-slate-100">
    <div class="max-w-md mx-auto px-5 h-16 flex justify-between items-center">
        
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center shadow-sm">
                
                <!-- ✅ LOGO DARI PUBLIC/IMG -->
                <img src="{{ asset('img/logo.png') }}" class="w-6 h-6 object-contain" alt="Logo">
            
            </div>

            <div class="flex flex-col">
                <span class="font-extrabold text-slate-800 leading-none text-base tracking-tight uppercase">SAPA</span>
                <span class="text-[9px] font-semibold text-slate-400 uppercase tracking-widest mt-0.5">Paramadina</span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="event.preventDefault(); confirmLogout();" class="w-10 h-10 rounded-full bg-white border border-slate-100 flex items-center justify-center text-slate-400 shadow-sm active:scale-90 transition-all">
                <i class="fa-solid fa-right-from-bracket text-sm"></i>
            </button>
        </div>

        <form action="{{ route('logout') }}" method="POST" id="logout-form" class="hidden">
            @csrf
        </form>
    </div>
</header>

<main class="max-w-md mx-auto pb-32">
    @yield('content')
</main>

@if(!request()->routeIs('scan.area'))
<nav id="bottom-nav" class="fixed bottom-0 left-0 right-0 z-[60] bg-white border-t border-slate-100 flex items-center justify-around pb-6 pt-3 px-4 shadow-[0_-10px_25px_rgba(0,0,0,0.03)]">
    
    <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1.5 {{ request()->routeIs('dashboard') ? 'active-nav-item' : 'text-slate-300' }}">
        <i class="fa-solid fa-house text-xl"></i>
        <span class="text-[10px] font-bold">Beranda</span>
    </a>

    <div class="relative -mt-12">
        <a href="{{ route('scan.area') }}" class="w-16 h-16 bg-slate-900 rounded-full flex items-center justify-center text-white shadow-xl border-[6px] border-[#F2F5F9] active:scale-90 transition-transform">
            <i class="fa-solid fa-qrcode text-2xl"></i>
        </a>
        <span class="absolute -bottom-7 left-1/2 -translate-x-1/2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Scan</span>
    </div>

    <a href="{{ route('profile') }}" class="flex flex-col items-center gap-1.5 {{ request()->routeIs('profile') ? 'active-nav-item' : 'text-slate-300' }}">
        <i class="fa-solid fa-user-circle text-xl"></i>
        <span class="text-[10px] font-bold">Profil</span>
    </a>

</nav>
@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Logout?',
            text: "Sesi lo bakal berakhir di sini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1e3a8a',
            cancelButtonColor: '#f1f5f9',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: '<span class="text-slate-500 font-bold">Batal</span>',
            customClass: {
                popup: 'rounded-3xl',
                confirmButton: 'rounded-xl px-6',
                cancelButton: 'rounded-xl px-6'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }

    // Scroll Behavior
    let lastScrollTop = 0;
    const nav = document.getElementById('bottom-nav');

    window.addEventListener("scroll", () => {
        let st = window.pageYOffset || document.documentElement.scrollTop;

        if (st > lastScrollTop && st > 100) {
            nav.classList.add("nav-hidden");
        } else {
            nav.classList.remove("nav-hidden");
        }

        lastScrollTop = st <= 0 ? 0 : st;
    });
</script>
</body>
</html>