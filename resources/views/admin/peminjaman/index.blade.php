@extends('layouts.admin')

@section('content')
<h2 class="text-2xl font-bold text-gray-800 mb-6">Approval Peminjaman</h2>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b">
            <tr class="text-[10px] font-black uppercase text-gray-400">
                <th class="p-4">Mahasiswa</th>
                <th class="p-4">Rincian Aset & Ruangan</th>
                <th class="p-4">Waktu Pinjam</th>
                <th class="p-4">Status</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($requests as $r)
            <tr class="text-sm">
                
                <!-- Kolom Mahasiswa -->
                <td class="p-4 font-bold align-top">
                    <span class="block text-gray-800">{{ $r->user->name }}</span>
                    <span class="text-[10px] text-gray-400 font-medium mt-1 block">Alasan: {{ $r->reason ?? '-' }}</span>
                </td>
                
                <!-- Kolom Rincian Aset (Looping Details) -->
                <td class="p-4 align-top">
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ $r->details->count() }} Macam Barang Dipinjam:</p>
                        <ul class="list-disc pl-4 text-xs font-medium text-gray-600 space-y-1">
                            @foreach($r->details as $detail)
                                <li>
                                    <span class="font-bold text-brand">{{ $detail->asset->name }}</span> 
                                    <span class="text-gray-400">({{ $detail->quantity }} Unit)</span> 
                                    - <span class="text-[9px] uppercase tracking-widest text-gray-400">{{ $detail->asset->room->name ?? 'Umum' }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </td>
                
                <!-- Kolom Waktu -->
                <td class="p-4 text-xs text-gray-500 font-medium align-top">
                    <div class="space-y-1">
                        <p class="text-gray-800"><i class="fa-regular fa-clock text-gray-400 mr-1"></i> {{ \Carbon\Carbon::parse($r->start_time)->format('d M, H:i') }}</p>
                        <p class="text-gray-400">S/D : {{ \Carbon\Carbon::parse($r->end_time)->format('H:i') }}</p>
                    </div>
                </td>
                
                <!-- Kolom Status -->
                <td class="p-4 align-top">
                    <span class="px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border
                        {{ $r->status == 'pending' ? 'bg-yellow-50 text-yellow-600 border-yellow-200' : ($r->status == 'active' ? 'bg-blue-50 text-brand border-blue-200' : ($r->status == 'returned' ? 'bg-green-50 text-green-600 border-green-200' : 'bg-red-50 text-red-600 border-red-200')) }}">
                        {{ $r->status }}
                    </span>
                </td>
                
                <!-- Kolom Aksi -->
                <td class="p-4 align-top">
                    <div class="flex justify-center gap-2">
                        @if($r->status == 'pending')
                            <form action="{{ route('admin.approve', $r->id) }}" method="POST">
                                @csrf
                                <button class="bg-green-100 hover:bg-green-500 text-green-600 hover:text-white transition-colors w-8 h-8 rounded-xl flex items-center justify-center shadow-sm"><i class="fa-solid fa-check"></i></button>
                            </form>
                            <form action="{{ route('admin.reject', $r->id) }}" method="POST" onsubmit="return confirm('Tolak peminjaman ini?')">
                                @csrf
                                <button class="bg-red-100 hover:bg-red-500 text-red-600 hover:text-white transition-colors w-8 h-8 rounded-xl flex items-center justify-center shadow-sm"><i class="fa-solid fa-xmark"></i></button>
                            </form>
                        @else
                            <span class="text-gray-300 italic text-[10px] uppercase font-black tracking-widest bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">Selesai</span>
                        @endif
                    </div>
                </td>
                
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection