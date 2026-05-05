@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Ruangan</h2>
            <p class="text-gray-500 text-sm">Kelola akses pintu masuk Lab & Fasilitas</p>
        </div>
        <a href="{{ route('admin.rooms.create') }}"
            class="bg-blue-900 hover:bg-blue-800 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition flex items-center gap-2 shadow-lg shadow-blue-100">
            <i class="fa-solid fa-plus"></i> Tambah Ruangan Baru
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="p-4 text-xs uppercase text-gray-500 font-bold tracking-wider">Nama Ruangan</th>
                        <th class="p-4 text-xs uppercase text-gray-500 font-bold tracking-wider w-1/3">Prodi Pemilik</th>
                        <th class="p-4 text-xs uppercase text-gray-500 font-bold tracking-wider">Token QR Pintu</th>
                        <th class="p-4 text-xs uppercase text-gray-500 font-bold tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($rooms as $room)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 font-bold text-gray-800">{{ $room->name }}</td>
                            <td class="p-4 text-sm text-gray-600">
                                <!-- UPDATE: Looping semua prodi yang gabung di lab ini -->
                                <div class="flex flex-wrap gap-1.5">
                                    @forelse($room->prodis as $prodi)
                                        <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded-lg font-medium text-[10px]">
                                            {{ $prodi->nama_prodi }}
                                        </span>
                                    @empty
                                        <span class="text-[10px] text-gray-400 italic">Belum diset</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="p-4">
                                <code
                                    class="text-xs font-mono bg-gray-100 px-2 py-1 rounded text-pink-600">{{ $room->qr_code_token }}</code>
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('admin.rooms.show', $room->id) }}"
                                        class="text-blue-500 hover:text-blue-700" title="Lihat & Cetak QR">
                                        <i class="fa-solid fa-qrcode"></i>
                                    </a>
                                    <a href="{{ route('admin.rooms.edit', $room->id) }}"
                                        class="text-amber-500 hover:text-amber-700" title="Edit Ruangan">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus ruangan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection