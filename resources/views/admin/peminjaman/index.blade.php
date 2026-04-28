@extends('layouts.admin')
@section('content')
<h2 class="text-2xl font-bold text-gray-800 mb-6">Approval Peminjaman</h2>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b">
            <tr class="text-[10px] font-black uppercase text-gray-400">
                <th class="p-4">Mahasiswa</th>
                <th class="p-4">Aset & Ruangan</th>
                <th class="p-4">Waktu Pinjam</th>
                <th class="p-4">Status</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($requests as $r)
            <tr class="text-sm">
                <td class="p-4 font-bold">{{ $r->user->name }}</td>
                <td class="p-4">
                    <span class="block font-medium">{{ $r->asset->name }}</span>
                    <span class="text-[10px] text-gray-400 uppercase tracking-widest">{{ $r->asset->room->name }}</span>
                </td>
                <td class="p-4 text-xs text-gray-500">
                    {{ \Carbon\Carbon::parse($r->start_time)->format('d M, H:i') }} - {{ \Carbon\Carbon::parse($r->end_time)->format('H:i') }}
                </td>
                <td class="p-4">
                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase 
                        {{ $r->status == 'pending' ? 'bg-yellow-100 text-yellow-600' : ($r->status == 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600') }}">
                        {{ $r->status }}
                    </span>
                </td>
                <td class="p-4 flex justify-center gap-2">
                    @if($r->status == 'pending')
                    <form action="{{ route('admin.approve', $r->id) }}" method="POST">
                        @csrf
                        <button class="bg-green-600 text-white p-2 rounded-lg text-xs"><i class="fa-solid fa-check"></i></button>
                    </form>
                    <form action="{{ route('admin.reject', $r->id) }}" method="POST" onsubmit="return confirm('Tolak peminjaman ini?')">
                        @csrf
                        <button class="bg-red-500 text-white p-2 rounded-lg text-xs"><i class="fa-solid fa-xmark"></i></button>
                    </form>
                    @else
                    <span class="text-gray-300 italic text-xs uppercase font-bold">Processed</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection