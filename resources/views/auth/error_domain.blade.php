<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - SAPA Paramadina</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] }, colors: { brand: '#1e3a8a' } } } }
    </script>
</head>
<body class="bg-slate-50 h-screen flex items-center justify-center p-5 font-sans">

    <!-- Card Container -->
    <div class="max-w-md w-full bg-white rounded-[2rem] p-8 text-center shadow-[0_20px_60px_rgba(0,0,0,0.05)] border border-red-50 relative overflow-hidden">
        
        <!-- Background Merah Halus di Atas -->
        <div class="absolute top-0 left-0 right-0 h-32 bg-red-50/50"></div>

        <div class="relative z-10">
            <!-- Icon Silang (X) -->
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner border-[6px] border-white">
                <i class="fa-solid fa-xmark text-4xl text-red-500"></i>
            </div>

            <!-- Pesan Error -->
            <h1 class="text-2xl font-black text-slate-800 mb-2">Akses Diblokir!</h1>
            <p class="text-[11px] font-bold text-red-500 uppercase tracking-widest mb-6">Domain Email Tidak Valid</p>
            
            <p class="text-sm font-medium text-slate-500 leading-relaxed mb-8 px-2">
                Sistem SAPA hanya dapat diakses menggunakan email resmi institusi. Pastikan Anda login menggunakan akun <strong class="text-slate-800">@paramadina.ac.id</strong> (Dosen/Staff) atau <strong class="text-slate-800">@students.paramadina.ac.id</strong> (Mahasiswa).
            </p>

            <!-- Tombol Kembali -->
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 w-full bg-brand text-white font-bold py-4 px-6 rounded-xl hover:bg-blue-900 active:scale-95 transition-all shadow-lg shadow-blue-900/20">
                <i class="fa-solid fa-rotate-left"></i> Kembali ke Halaman Login
            </a>
        </div>
    </div>

</body>
</html>