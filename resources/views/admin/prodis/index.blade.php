@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Manajemen Program Studi</h2>
    <p class="text-gray-500 text-sm">Daftar prodi yang terdaftar di sistem SAPA</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="p-4 text-xs font-bold uppercase text-gray-400">Nama Program Studi</th>
                    <th class="p-4 text-xs font-bold uppercase text-gray-400 text-center">Total Aset</th>
                    <th class="p-4 text-xs font-bold uppercase text-gray-400 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($prodis as $p)
                <tr>
                    <td class="p-4 font-bold text-gray-700">{{ $p->nama_prodi }}</td>
                    <td class="p-4 text-center">
                        <span class="bg-green-50 text-green-600 px-3 py-1 rounded-full text-xs font-bold">
                            {{ $p->assets_count }} Items
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <form action="{{ route('admin.prodis.destroy', $p->id) }}" method="POST">
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
        <h3 class="font-bold text-gray-700 mb-4 text-sm uppercase italic">Tambah Prodi</h3>
        <form action="{{ route('admin.prodis.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <input type="text" name="nama_prodi" placeholder="Misal: Teknik Informatika" 
                       class="w-full px-4 py-3 border rounded-xl outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
            </div>
            <button class="w-full bg-blue-900 text-white py-3 rounded-xl font-bold text-sm">Simpan</button>
        </form>
    </div>
</div>
@endsection