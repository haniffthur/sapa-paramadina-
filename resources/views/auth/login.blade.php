<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SAPA Paramadina</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md text-center">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-blue-900">SAPA</h1>
            <p class="text-gray-500 uppercase tracking-widest text-sm">Paramadina Access</p>
        </div>

        <h2 class="text-xl font-semibold mb-2">Selamat Datang</h2>
        <p class="text-gray-600 mb-8">Silakan login menggunakan akun email institusi Anda untuk meminjam fasilitas.</p>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <a href="{{ route('google.login') }}" 
           class="flex items-center justify-center gap-3 w-full bg-white border border-gray-300 text-gray-700 font-semibold py-3 px-4 rounded-lg hover:bg-gray-50 transition duration-200">
            <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-6 h-6" alt="Google Logo">
            Login dengan Google
        </a>

        <div class="mt-8 pt-6 border-t border-gray-100">
            <p class="text-xs text-gray-400">
                Fakultas Ilmu Rekayasa <br> Universitas Paramadina
            </p>
        </div>
    </div>

</body>
</html>