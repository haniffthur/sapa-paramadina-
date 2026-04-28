@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">Detail QR Ruangan</h2>
    <a href="{{ route('admin.rooms.index') }}" class="text-sm text-gray-500 hover:text-blue-600">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <span class="text-[10px] font-black uppercase tracking-widest text-blue-500">{{ $room->prodi->nama_prodi }}</span>
            <h1 class="text-3xl font-bold text-gray-800 mt-1">{{ $room->name }}</h1>
            <p class="text-gray-500 mt-2">{{ $room->description ?? 'Tidak ada deskripsi ruangan.' }}</p>

            <div class="mt-8 pt-8 border-t border-gray-50">
                <h4 class="text-xs font-bold text-gray-400 uppercase mb-4">Aset yang terdaftar di ruangan ini:</h4>
                <div class="grid grid-cols-1 gap-2">
                    @forelse($room->assets as $asset)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                            <span class="text-sm font-semibold text-gray-700">{{ $asset->name }}</span>
                            <span class="text-[10px] bg-white px-2 py-1 rounded border text-gray-400 uppercase font-bold">{{ $asset->category->name }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 italic text-center py-4">Belum ada aset yang dipindahkan ke ruangan ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-center h-fit">
        <p class="text-[10px] font-black text-gray-400 uppercase mb-6 tracking-widest">Cetak QR Code Pintu</p>
        
        <div class="bg-white p-4 rounded-2xl inline-block border-2 border-dashed border-gray-200 mb-6">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($qrUrl) }}" alt="QR Code Pintu">
        </div>
        
        <div class="bg-blue-50 p-4 rounded-xl mb-6">
            <p class="text-[9px] text-blue-400 font-mono break-all mb-1 uppercase">URL Akses:</p>
            <p class="text-[10px] text-blue-900 font-bold break-all">{{ $qrUrl }}</p>
        </div>
        
        <button onclick="window.print()" class="w-full bg-blue-900 text-white py-4 rounded-2xl font-bold text-sm hover:bg-black transition shadow-lg shadow-blue-100 flex items-center justify-center gap-2">
            <i class="fa-solid fa-print"></i> Cetak Label Pintu
        </button>
    </div>
</div>
@endsection