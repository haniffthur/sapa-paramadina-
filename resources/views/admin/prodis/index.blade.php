@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Program Studi</h2>
        <p class="text-gray-500 text-sm">Kelola data prodi yang ada di Universitas Paramadina</p>
    </div>
    <button type="button" onclick="openModal('createModal')" class="bg-blue-900 hover:bg-blue-800 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition flex items-center gap-2 shadow-lg shadow-blue-100">
        <i class="fa-solid fa-plus"></i> Tambah Prodi
    </button>
</div>

<!-- Menampilkan Pesan Error / Success di atas tabel -->
@if(session('success'))
    <div class="mb-4 bg-green-50 text-green-600 p-4 rounded-xl border border-green-100 text-sm font-bold flex items-center gap-2">
        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-4 bg-red-50 text-red-600 p-4 rounded-xl border border-red-100 text-sm font-bold">
        <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="p-4 text-xs uppercase text-gray-500 font-bold tracking-wider">Nama Program Studi</th>
                    <th class="p-4 text-xs uppercase text-gray-500 font-bold tracking-wider text-center">Jumlah Ruangan</th>
                    <th class="p-4 text-xs uppercase text-gray-500 font-bold tracking-wider text-center">Total Aset (Barang)</th>
                    <th class="p-4 text-xs uppercase text-gray-500 font-bold tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($prodis as $prodi)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 font-bold text-gray-800">{{ $prodi->nama_prodi }}</td>
                    
                    <td class="p-4 text-center">
                        <span class="bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg font-bold text-xs">
                            {{ $prodi->rooms_count }} Ruangan
                        </span>
                    </td>
                    
                    <td class="p-4 text-center">
                        <span class="bg-green-50 text-green-700 px-3 py-1.5 rounded-lg font-bold text-xs">
                            {{ $prodi->assets_count }} Unit
                        </span>
                    </td>
                    
                    <td class="p-4 text-right">
                        <div class="flex justify-end gap-3">
                            <!-- UPDATE: Tombol Edit sekarang buka Modal sesuai ID -->
                            <button type="button" onclick="openModal('editModal-{{ $prodi->id }}')" class="text-amber-500 hover:text-amber-700" title="Edit Prodi">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            
                            <form action="{{ route('admin.prodis.destroy', $prodi->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus Prodi {{ $prodi->nama_prodi }}? Semua akses ruangannya bakal terputus loh!')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach

                @if($prodis->isEmpty())
                <tr>
                    <td colspan="4" class="p-10 text-center text-gray-400 italic">
                        Belum ada data Program Studi. Silakan tambah baru.
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- ================= MODALS SECTION ================= -->

<!-- 1. MODAL TAMBAH PRODI -->
<div id="createModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity opacity-0" id="backdrop-createModal" onclick="closeModal('createModal')"></div>
    <div class="bg-white w-full max-w-md rounded-2xl p-6 relative z-10 transform scale-95 opacity-0 transition-all duration-300 shadow-2xl" id="content-createModal">
        <div class="flex justify-between items-start mb-5">
            <div>
                <h3 class="text-xl font-bold text-gray-800">Tambah Prodi Baru</h3>
                <p class="text-xs text-gray-500 mt-1">Masukkan data program studi</p>
            </div>
            <button type="button" onclick="closeModal('createModal')" class="text-gray-400 hover:text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.prodis.store') }}" method="POST">
            @csrf
            <!-- Flag biar JS tau ini form create -->
            <input type="hidden" name="form_type" value="create"> 
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Program Studi</label>
                    <input type="text" name="nama_prodi" value="{{ old('form_type') == 'create' ? old('nama_prodi') : '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="Contoh: Desain Komunikasi Visual" required>
                </div>
                <button type="submit" class="w-full bg-blue-900 text-white font-bold py-4 rounded-xl hover:bg-blue-800 transition shadow-lg shadow-blue-100">
                    Simpan Prodi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 2. KUMPULAN MODAL EDIT PRODI (Di-generate sesuai jumlah data) -->
@foreach($prodis as $prodi)
<div id="editModal-{{ $prodi->id }}" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity opacity-0" id="backdrop-editModal-{{ $prodi->id }}" onclick="closeModal('editModal-{{ $prodi->id }}')"></div>
    <div class="bg-white w-full max-w-md rounded-2xl p-6 relative z-10 transform scale-95 opacity-0 transition-all duration-300 shadow-2xl" id="content-editModal-{{ $prodi->id }}">
        <div class="flex justify-between items-start mb-5">
            <div>
                <h3 class="text-xl font-bold text-gray-800">Edit Data Prodi</h3>
                <p class="text-xs text-gray-500 mt-1">Ubah nama program studi</p>
            </div>
            <button type="button" onclick="closeModal('editModal-{{ $prodi->id }}')" class="text-gray-400 hover:text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.prodis.update', $prodi->id) }}" method="POST">
            @csrf
            @method('PUT')
            <!-- Flag biar JS tau modal mana yang lagi error -->
            <input type="hidden" name="edit_prodi_id" value="{{ $prodi->id }}"> 
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Program Studi</label>
                    <input type="text" name="nama_prodi" value="{{ old('edit_prodi_id') == $prodi->id ? old('nama_prodi') : $prodi->nama_prodi }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition" required>
                </div>
                <button type="submit" class="w-full bg-amber-500 text-white font-bold py-4 rounded-xl hover:bg-amber-600 transition shadow-lg shadow-amber-100">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endforeach

<!-- SCRIPT UNTUK ANIMASI MODAL -->
<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        const backdrop = document.getElementById('backdrop-' + id);
        const content = document.getElementById('content-' + id);
        
        modal.classList.remove('hidden');
        void modal.offsetWidth; // Trigger reflow 
        
        backdrop.classList.remove('opacity-0');
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        const backdrop = document.getElementById('backdrop-' + id);
        const content = document.getElementById('content-' + id);
        
        backdrop.classList.add('opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // AUTO-OPEN MODAL KALAU ADA ERROR VALIDASI
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            @if(old('edit_prodi_id'))
                // Kalau errornya dari form edit, buka modal edit spesifik
                openModal('editModal-{{ old("edit_prodi_id") }}');
            @elseif(old('form_type') == 'create')
                // Kalau errornya dari form create, buka modal create
                openModal('createModal');
            @endif
        });
    @endif
</script>
@endsection