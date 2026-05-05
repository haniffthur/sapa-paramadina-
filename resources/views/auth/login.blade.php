<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login - SAPA Paramadina</title>
    
    <!-- Integrasi Tailwind & Font -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Konfigurasi Tema Senada dengan App -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#1e3a8a',
                        accent: '#3b82f6',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body { -webkit-tap-highlight-color: transparent; }
        /* Background Pattern Titik-titik Modern */
        .bg-pattern {
            background-color: #F8FAFC;
            background-image: radial-gradient(#CBD5E1 1px, transparent 1px);
            background-size: 24px 24px;
        }
        /* Animasi Masuk Smooth */
        .fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-pattern h-screen flex flex-col items-center justify-center relative overflow-hidden text-slate-800">

    <!-- Decorative Blurred Blobs (Background) -->
    <div class="absolute top-[-15%] left-[-10%] w-96 h-96 bg-blue-400/20 rounded-full blur-[80px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-brand/10 rounded-full blur-[80px] pointer-events-none"></div>

    <!-- Login Container -->
    <div class="w-full max-w-sm px-6 relative z-10 fade-in-up">
        
        <!-- Main Card -->
        <div class="bg-white/80 backdrop-blur-xl p-8 rounded-[2.5rem] shadow-[0_20px_60px_rgba(30,58,138,0.08)] border border-white">
            
            <!-- Logo Section -->
           <!-- Logo Section -->
<div class="text-center mb-8 mt-2">
    <!-- Box Logo -->
    <div class="w-16 h-16 bg-white border border-slate-100 rounded-2xl mx-auto flex items-center justify-center mb-5 shadow-xl shadow-blue-900/10 transform -rotate-3 hover:rotate-0 transition-transform duration-300">
        <img src="{{ asset('img/logo.png') }}" alt="Logo Paramadina" class="w-10 h-10 object-contain transform rotate-3">
    </div>
    
    <!-- Teks Digabung Jadi Satu Baris -->
    <h1 class="text-2xl font-black text-slate-800 tracking-tight leading-none">
        SAPA <span class="text-brand font-extrabold">PARAMADINA</span>
    </h1>
</div>

            <!-- Welcome Text -->
            <div class="text-center mb-8">
                <h2 class="text-lg font-extrabold text-slate-800 mb-1.5">Selamat Datang </h2>
                <p class="text-xs font-medium text-slate-500 leading-relaxed px-2">
                    Gunakan email institusi <strong class="text-slate-700">@paramadina.ac.id</strong> untuk mengakses sistem fasilitas kampus.
                </p>
            </div>

            <!-- Error Notification (Modern Design) -->
            @if(session('error'))
                <div class="bg-red-50 border border-red-100 text-red-600 p-4 rounded-2xl mb-6 text-[10px] font-bold flex items-start gap-3 shadow-sm">
                    <i class="fa-solid fa-circle-exclamation text-sm mt-0.5"></i>
                    <p class="leading-relaxed">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Google Login Button -->
            <a href="{{ route('google.login') }}" class="group relative flex items-center justify-center gap-3 w-full bg-white border border-slate-200 text-slate-700 font-bold py-4 px-4 rounded-2xl shadow-sm hover:shadow-md hover:border-slate-300 active:scale-95 transition-all duration-300">
                <!-- SVG Google Asli dengan warna -->
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5 group-hover:scale-110 transition-transform duration-300" alt="Google Logo">
                <span class="text-sm">Lanjutkan dengan Google</span>
            </a>

        </div>

        <!-- Footer -->
        <div class="text-center mt-10">
            <p class="text-[9px] font-extrabold text-slate-400 uppercase tracking-widest leading-relaxed">
                Fakultas Ilmu Rekayasa <br> Universitas Paramadina
            </p>
        </div>

    </div>

</body>
</html>