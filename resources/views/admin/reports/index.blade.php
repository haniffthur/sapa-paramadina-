@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Riwayat & Laporan</h2>
        <p class="text-gray-500 text-sm">Rekapitulasi penggunaan fasilitas FIR</p>
    </div>
    <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-black transition">
        <i class="fa-solid fa-print"></i> Cetak Laporan
    </button>
</div>

<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
    <form action="{{ route('admin.report') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
            <label class="text-[10px] font-bold text-gray-400 uppercase">Status</label>
            <select name="status" class="w-full mt-1 border rounded-lg p-2 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div>
            <label class="text-[10px] font-bold text-gray-400 uppercase">Mulai</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full mt-1 border rounded-lg p-2 text-sm outline-none">
        </div>
        <div>
            <label class="text-[10px] font-bold text-gray-400 uppercase">Sampai</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full mt-1 border rounded-lg p-2 text-sm outline-none">
        </div>
        <button type="submit" class="bg-blue-600 text-white py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition">
            Apply Filter
        </button>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="p-4 text-xs font-bold text-gray-400 uppercase">Waktu</th>
                <th class="p-4 text-xs font-bold text-gray-400 uppercase">Mahasiswa</th>
                <th class="p-4 text-xs font-bold text-gray-400 uppercase">Fasilitas</th>
                <th class="p-4 text-xs font-bold text-gray-400 uppercase text-center">Status</th>
                <th class="p-4 text-xs font-bold text-gray-400 uppercase">Catatan Admin</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($history as $item)
            <tr class="hover:bg-gray-50">
                <td class="p-4 text-sm text-gray-600">
                    {{ $item->created_at->format('d/m/Y') }} <br>
                    <span class="text-[10px] text-gray-400">{{ $item->created_at->format('H:i') }} WIB</span>
                </td>
                <td class="p-4">
                    <p class="text-sm font-bold text-gray-800 leading-none">{{ $item->user->name }}</p>
                    <p class="text-[10px] text-gray-400 mt-1">{{ $item->user->email }}</p>
                </td>
                <td class="p-4 text-sm font-medium text-gray-700">
                    {{ $item->asset->name }}
                </td>
                <td class="p-4 text-center">
                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase
                        {{ $item->status == 'approved' ? 'bg-green-100 text-green-700' : 
                          ($item->status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ $item->status }}
                    </span>
                </td>
                <td class="p-4 text-xs text-gray-500 italic">
                    {{ $item->admin_note ?? '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="p-12 text-center text-gray-400 italic">Data peminjaman tidak ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t bg-gray-50">
        {{ $history->links() }}
    </div>
</div>
@endsection