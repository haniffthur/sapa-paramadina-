@extends('layouts.petugas')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Riwayat Laporan Kerusakan</h2>
        <p class="text-gray-500 text-sm">Pantau status perbaikan aset yang telah Anda laporkan</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="p-4 text-xs uppercase text-gray-500 font-bold">Aset</th>
                <th class="p-4 text-xs uppercase text-gray-500 font-bold">Deskripsi Kerusakan</th>
                <th class="p-4 text-xs uppercase text-gray-500 font-bold text-center">Tanggal Lapor</th>
                <th class="p-4 text-xs uppercase text-gray-500 font-bold text-center">Status</th>
                <th class="p-4 text-xs uppercase text-gray-500 font-bold text-right">Foto</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($reports as $report)
            <tr class="hover:bg-gray-50 transition">
                <td class="p-4">
                    <p class="font-bold text-gray-800 text-sm">{{ $report->asset->name }}</p>
                    <p class="text-[10px] text-blue-600 font-bold uppercase tracking-widest">
                        <i class="fa-solid fa-location-dot"></i> {{ $report->asset->room->name ?? 'N/A' }}
                    </p>
                </td>
                <td class="p-4">
                    <p class="text-xs text-gray-600 line-clamp-2">{{ $report->deskripsi_kerusakan }}</p>
                </td>
                <td class="p-4 text-center">
                    <p class="text-xs font-bold text-gray-700">{{ $report->created_at->format('d M Y') }}</p>
                    <p class="text-[10px] text-gray-400">{{ $report->created_at->format('H:i') }} WIB</p>
                </td>
                <td class="p-4 text-center">
                    @if($report->status == 'menunggu')
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-700">Menunggu</span>
                    @elseif($report->status == 'diproses')
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-blue-100 text-blue-700">Diproses</span>
                    @else
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-green-100 text-green-700">Selesai</span>
                    @endif
                </td>
                <td class="p-4 text-right">
                    @if($report->foto_kerusakan)
                        <a href="{{ asset('storage/' . $report->foto_kerusakan) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                            <i class="fa-solid fa-image text-lg"></i>
                        </a>
                    @else
                        <span class="text-gray-300 italic text-xs">No Photo</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4 bg-gray-50">
        {{ $reports->links() }}
    </div>
</div>
@endsection