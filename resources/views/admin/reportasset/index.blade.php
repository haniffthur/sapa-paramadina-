@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Laporan Kerusakan Aset</h2>
        <p class="text-gray-500 text-sm">Kelola laporan teknis dari petugas lapangan Paramadina</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="p-4 text-xs uppercase text-gray-500 font-bold">Aset & Lokasi</th>
                <th class="p-4 text-xs uppercase text-gray-500 font-bold">Pelapor (Petugas)</th>
                <th class="p-4 text-xs uppercase text-gray-500 font-bold">Deskripsi Kerusakan</th>
                <th class="p-4 text-xs uppercase text-gray-500 font-bold text-center">Status</th>
                <th class="p-4 text-xs uppercase text-gray-500 font-bold text-right">Lampiran</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($reports as $report)
            <tr class="hover:bg-gray-50 transition text-sm">
                <td class="p-4">
                    <p class="font-bold text-gray-800">{{ $report->asset->name }}</p>
                    <p class="text-[10px] text-blue-600 font-bold uppercase tracking-widest italic">
                        <i class="fa-solid fa-location-dot"></i> {{ $report->asset->room->name ?? 'Area Umum' }}
                    </p>
                </td>
                <td class="p-4">
                    <div class="flex items-center gap-2">
                        <img src="{{ $report->petugas->avatar }}" class="w-7 h-7 rounded-full border shadow-sm object-cover">
                        <p class="font-semibold text-gray-700">{{ $report->petugas->name }}</p>
                    </div>
                </td>
                <td class="p-4">
                    <p class="text-gray-600 line-clamp-1 italic" title="{{ $report->deskripsi_kerusakan }}">
                        "{{ $report->deskripsi_kerusakan }}"
                    </p>
                </td>
                <td class="p-4 text-center">
                    <form action="{{ route('admin.asset-reports.update', $report->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status" onchange="this.form.submit()" 
                            class="text-[10px] font-black uppercase rounded-lg px-3 py-1.5 outline-none border cursor-pointer transition-all
                            {{ $report->status == 'menunggu' ? 'bg-amber-50 text-amber-600 border-amber-200 hover:bg-amber-100' : 
                               ($report->status == 'diproses' ? 'bg-blue-50 text-blue-600 border-blue-200 hover:bg-blue-100' : 
                               'bg-green-50 text-green-600 border-green-200 hover:bg-green-100') }}">
                            <option value="menunggu" {{ $report->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="diproses" {{ $report->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ $report->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </form>
                </td>
                <td class="p-4 text-right">
                    @if($report->foto_kerusakan)
                        <a href="{{ asset('storage/' . $report->foto_kerusakan) }}" target="_blank" 
                           class="bg-blue-900 text-white px-3 py-2 rounded-xl hover:bg-black transition inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest shadow-sm">
                            <i class="fa-solid fa-camera"></i> Lihat Foto
                        </a>
                    @else
                        <span class="text-gray-300 text-[10px] font-bold uppercase italic">No File</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    @if($reports->hasPages())
    <div class="p-4 bg-gray-50 border-t">
        {{ $reports->links() }}
    </div>
    @endif
</div>
@endsection