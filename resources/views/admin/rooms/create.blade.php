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

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Prodi Pengelola</label>
                    <select name="prodi_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition" required>
                        <option value="">Pilih Prodi</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                        @endforeach
                    </select>
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
@endsection