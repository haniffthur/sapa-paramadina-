@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800">Ringkasan Sistem</h2>
    <p class="text-gray-500 text-sm">Update aktivitas SAPA hari ini.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-red-500">
        <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Menunggu Persetujuan</p>
        <p class="text-3xl font-black text-gray-800 mt-1">{{ $pending_count }}</p>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-green-500">
        <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Peminjaman Aktif</p>
        <p class="text-3xl font-black text-gray-800 mt-1">{{ $active_loan }}</p>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-blue-500">
        <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Aset FIR</p>
        <p class="text-3xl font-black text-gray-800 mt-1">{{ $total_assets }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-700 text-lg">Permintaan Terbaru</h3>
            <a href="{{ route('admin.peminjaman.index') }}" class="text-blue-600 text-xs font-bold hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-[10px] uppercase font-bold text-gray-400">
                    <tr>
                        <th class="p-4">Mahasiswa</th>
                        <th class="p-4">Ruangan/Aset</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recent_requests as $request)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $request->user->avatar }}" class="w-8 h-8 rounded-full border">
                                <div>
                                    <p class="text-sm font-bold text-gray-800 leading-none">{{ $request->user->name }}</p>
                                    <p class="text-[10px] text-gray-500 mt-1">{{ $request->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="text-xs font-medium text-blue-700 bg-blue-50 px-2 py-1 rounded">
                                {{ $request->asset->name }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-2">
                                <form action="{{ route('admin.approve', $request->id) }}" method="POST">
                                    @csrf
                                    <button class="bg-green-600 text-white p-2 rounded-lg hover:bg-green-700 transition shadow-sm">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                                <button onclick="showRejectModal({{ $request->id }})" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600 transition shadow-sm">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-8 text-center text-gray-400 italic text-sm">Tidak ada permintaan masuk hari ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="lg:col-span-1 space-y-6">
        <div class="bg-blue-900 text-white p-6 rounded-2xl shadow-lg">
            <h4 class="font-bold mb-2 flex items-center gap-2">
                <i class="fa-solid fa-lightbulb text-yellow-400"></i> Tips Cepat
            </h4>
            <p class="text-sm opacity-80 leading-relaxed text-blue-100">
                Jangan lupa untuk selalu memeriksa kondisi fisik ruangan/barang sebelum melakukan **Approval** peminjaman kembali.
            </p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center">
            <p class="text-xs text-gray-400 font-bold uppercase mb-4">Cetak QR Code</p>
            <i class="fa-solid fa-qrcode text-5xl text-gray-200 mb-4"></i>
            <p class="text-xs text-gray-500 mb-4">Butuh label QR baru untuk Lab?</p>
            <a href="{{ route('admin.assets.index') }}" class="block bg-gray-100 text-gray-700 py-2 rounded-xl text-xs font-bold hover:bg-gray-200 transition">Ke Manajemen Aset</a>
        </div>
    </div>
</div>
@endsection