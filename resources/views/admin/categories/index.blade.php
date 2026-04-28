@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Kategori Aset</h2>
    <p class="text-gray-500 text-sm">Kelola pengelompokan fasilitas FIR</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="p-4 text-xs font-bold uppercase text-gray-400">Nama Kategori</th>
                    <th class="p-4 text-xs font-bold uppercase text-gray-400 text-center">Jumlah Aset</th>
                    <th class="p-4 text-xs font-bold uppercase text-gray-400 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($categories as $cat)
                <tr>
                    <td class="p-4 font-bold text-gray-700">{{ $cat->name }}</td>
                    <td class="p-4 text-center">
                        <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-bold">
                            {{ $cat->assets_count }} Items
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 text-sm font-bold">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-fit">
        <h3 class="font-bold text-gray-700 mb-4 text-sm uppercase">Tambah Kategori Baru</h3>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <input type="text" name="name" placeholder="Contoh: Elektronik" 
                       class="w-full px-4 py-3 border rounded-xl outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
            </div>
            <button class="w-full bg-blue-900 text-white py-3 rounded-xl font-bold text-sm">Simpan Kategori</button>
        </form>
    </div>
</div>
@endsection