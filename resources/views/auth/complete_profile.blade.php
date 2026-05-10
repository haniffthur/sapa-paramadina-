<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Profil - SAPA Paramadina</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-xl overflow-hidden">
        <div class="bg-blue-900 p-8 text-center">
            <h1 class="text-2xl font-bold text-white tracking-tighter">SAPA <span class="text-blue-300">PROFILE</span></h1>
            <p class="text-blue-100 text-xs mt-1">Satu langkah lagi untuk mulai menggunakan SAPA</p>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nomor Induk Mahasiswa (NIM)</label>
                <input type="text" name="nim" required placeholder="Contoh: 120101234"
                    class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50 text-gray-800 focus:ring-2 focus:ring-blue-900 outline-none transition font-semibold">
                @error('nim') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Program Studi</label>
                
                <div class="relative">
                    <select name="prodi_id" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-white text-gray-800 focus:ring-2 focus:ring-blue-900 outline-none transition font-semibold cursor-pointer appearance-none shadow-sm">
                        
                        <option value="" class="bg-white text-gray-500">-- Pilih Program Studi --</option>
                       @foreach($prodis as $prodi)
    <option value="{{ $prodi->id }}" class="bg-white text-gray-800 font-semibold">{{ $prodi->nama_prodi }}</option>
@endforeach
                    </select>
                    
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </div>
                </div>

            </div>

            <button type="submit" class="w-full bg-blue-900 text-white font-bold py-4 rounded-2xl hover:bg-black transition shadow-lg shadow-blue-200 active:scale-95">
                Simpan & Masuk Dashboard
            </button>
        </form>
    </div>
</body>
</html>