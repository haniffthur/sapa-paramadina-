@extends('layouts.petugas')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Pantau Pinjaman Aktif</h2>
        <p class="text-gray-500 text-sm">Monitor aset yang sedang digunakan di lingkungan Universitas Paramadina</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="p-4 text-xs uppercase text-gray-500 font-bold">Mahasiswa</th>
                <th class="p-4 text-xs uppercase text-gray-500 font-bold">Aset & Lokasi</th>
                <th class="p-4 text-xs uppercase text-gray-500 font-bold text-center">Waktu Pinjam</th>
                <th class="p-4 text-xs uppercase text-gray-500 font-bold text-center">Status</th>
                <th class="p-4 text-xs uppercase text-gray-500 font-bold text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($peminjamans as $loan)
            <tr class="hover:bg-gray-50 transition">
                <td class="p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                            {{ substr($loan->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-sm">{{ $loan->user->name }}</p>
                            <p class="text-[10px] text-gray-500 italic">{{ $loan->user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="p-4">
                    @foreach($loan->details as $detail)
                        <p class="text-sm font-semibold text-gray-800">{{ $detail->asset->name }}</p>
                        <p class="text-[10px] text-blue-600 font-bold uppercase tracking-widest">
                            <i class="fa-solid fa-location-dot"></i> {{ $detail->asset->room->name ?? 'Area Umum' }}
                        </p>
                    @endforeach
                </td>
                <td class="p-4 text-center">
                    <p class="text-xs font-bold text-gray-700">{{ \Carbon\Carbon::parse($loan->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($loan->end_time)->format('H:i') }}</p>
                    <p class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($loan->start_time)->format('d M Y') }}</p>
                </td>
                <td class="p-4 text-center">
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase 
                        {{ $loan->status == 'late' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ $loan->status }}
                    </span>
                </td>
                <td class="p-4 text-right">
                    <button onclick="openModal('reportModal-{{ $loan->id }}')" class="bg-red-50 text-red-600 hover:bg-red-600 hover:text-white p-2 rounded-xl transition shadow-sm border border-red-100">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4 bg-gray-50">
        {{ $peminjamans->links() }}
    </div>
</div>

{{-- Re-use Modal Lapor Kerusakan yang sudah kita buat sebelumnya di sini --}}

@endsection