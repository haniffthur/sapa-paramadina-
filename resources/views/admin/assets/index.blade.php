@extends('layouts.admin')
@section('content')
<div class="flex justify-between items-center mb-6 px-4">
    <h2 class="text-2xl font-black text-slate-800">Inventaris Aset</h2>
    <a href="{{ route('admin.assets.create') }}" class="bg-blue-900 text-white px-6 py-2.5 rounded-2xl font-bold text-sm shadow-lg shadow-blue-100 hover:bg-black transition-all">
        <i class="fa-solid fa-plus mr-2"></i> Tambah Aset Baru
    </a>
</div>

<div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden mx-4">
    <table class="w-full text-left">
        <thead class="bg-slate-50/50 border-b border-slate-100">
            <tr class="text-[10px] font-black uppercase text-slate-400 tracking-widest">
                <th class="p-6">Informasi Aset</th>
                <th class="p-6">Lokasi & Kategori</th>
                <th class="p-6">Audit</th>
                <th class="p-6 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @foreach($assets as $asset)
            <tr class="hover:bg-slate-50/30 transition-colors">
                <td class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                            @if($asset->image)
                                <img src="{{ asset('storage/' . $asset->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fa-solid fa-image"></i></div>
                            @endif
                        </div>
                        <div>
                            <p class="font-black text-slate-800 leading-tight">{{ $asset->name }}</p>
                            <p class="text-[10px] text-slate-400 mt-1 font-bold uppercase">Qty: {{ $asset->quantity }} Unit</p>
                        </div>
                    </div>
                </td>
                <td class="p-6">
                    <span class="block text-sm font-bold text-blue-600">{{ $asset->room->name }}</span>
                    <span class="text-[10px] text-slate-400 uppercase font-black tracking-widest">{{ $asset->category->name }}</span>
                </td>
                <td class="p-6">
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-blue-100 text-blue-700">
                        {{ $asset->kondisi }}
                    </span>
                    <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-tighter">Rp {{ number_format($asset->harga_beli, 0, ',', '.') }}</p>
                </td>
                <td class="p-6 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.assets.show', $asset->id) }}" class="w-10 h-10 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-900 hover:text-white transition-all"><i class="fa-solid fa-eye"></i></a>
                        <a href="{{ route('admin.assets.edit', $asset->id) }}" class="w-10 h-10 flex items-center justify-center bg-yellow-50 text-yellow-600 rounded-xl hover:bg-yellow-500 hover:text-white transition-all"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="{{ route('admin.assets.destroy', $asset->id) }}" method="POST" onsubmit="return confirm('Hapus aset?')">
                            @csrf @method('DELETE')
                            <button class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-600 rounded-xl hover:bg-red-500 hover:text-white transition-all"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection