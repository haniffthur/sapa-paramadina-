@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.rooms.index') }}" class="text-blue-600 text-sm font-semibold hover:underline">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <h2 class="text-2xl font-bold text-gray-800 mt-2">Buat Ruangan Baru</h2>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('admin.rooms.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Ruangan</label>
                    <input type="text" name="name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="Contoh: Lab Game & Multimedia" required>
                </div>

                <!-- DYNAMIC PRODI ROWS -->
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <label class="block text-sm font-semibold text-gray-700">Prodi Pengelola</label>
                        <button type="button" onclick="addProdiRow()" class="text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition">
                            <i class="fa-solid fa-plus"></i> Tambah Prodi
                        </button>
                    </div>

                    <div id="prodi-container" class="space-y-3">
                        <!-- Baris Pertama (Wajib Ada, Gak Bisa Dihapus) -->
                        <div class="flex gap-2 prodi-row">
                            <select name="prodi_ids[]" class="flex-1 px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition" required>
                                <option value="">-- Pilih Prodi --</option>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                @endforeach
                            </select>
                            <!-- Spacer biar sejajar sama yang bawah -->
                            <div class="w-[42px]"></div> 
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi (Opsional)</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="Jelaskan fungsi ruangan ini..."></textarea>
                </div>

                <button type="submit" class="w-full bg-blue-900 text-white font-bold py-4 rounded-xl hover:bg-blue-800 transition shadow-lg shadow-blue-100">
                    Simpan Ruangan & Generate QR Pintu
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function addProdiRow() {
        const container = document.getElementById('prodi-container');
        
        // Bikin element div baru
        const row = document.createElement('div');
        row.className = 'flex gap-2 prodi-row mt-3'; // mt-3 buat jarak antar row baru
        
        // HTML form select yang dikloning
        row.innerHTML = `
            <select name="prodi_ids[]" class="flex-1 px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition" required>
                <option value="">-- Pilih Prodi Tambahan --</option>
                @foreach($prodis as $prodi)
                    <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                @endforeach
            </select>
            <button type="button" onclick="this.parentElement.remove()" class="w-[42px] shrink-0 bg-red-50 text-red-500 hover:bg-red-100 rounded-xl flex items-center justify-center transition border border-red-100">
                <i class="fa-solid fa-xmark"></i>
            </button>
        `;
        
        container.appendChild(row);
    }
</script>
@endsection