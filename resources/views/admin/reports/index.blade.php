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
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
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
                <th class="p-4 text-xs font-bold text-gray-400 uppercase">Waktu Pinjam</th>
                <th class="p-4 text-xs font-bold text-gray-400 uppercase">Mahasiswa</th>
                <th class="p-4 text-xs font-bold text-gray-400 uppercase">Fasilitas</th>
                <th class="p-4 text-xs font-bold text-gray-400 uppercase text-center">Status</th>
                <th class="p-4 text-xs font-bold text-gray-400 uppercase text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($history as $item)
            <tr class="hover:bg-gray-50">
                <td class="p-4 text-sm text-gray-600">
                    <span class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($item->start_time)->format('d M Y') }}</span><br>
                    <span class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }} WIB</span>
                </td>
                <td class="p-4">
                    <p class="text-sm font-bold text-gray-800 leading-none">{{ $item->user->name }}</p>
                    <p class="text-[10px] text-gray-400 mt-1">{{ $item->user->email }}</p>
                </td>
                <td class="p-4 text-sm font-medium text-gray-700">
                    <!-- Update Penamaan Aset Biar Nggak Error -->
                    {{ $item->details->first()?->asset?->name ?? '-' }}
                    
                    @if($item->details->count() > 1)
                        <span class="ml-1 text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100 px-2 py-0.5 rounded-full">
                            +{{ $item->details->count() - 1 }} Item Lain
                        </span>
                    @endif
                </td>
                <td class="p-4 text-center">
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase
                        {{ $item->status == 'completed' ? 'bg-green-100 text-green-700' : 
                          ($item->status == 'rejected' ? 'bg-red-100 text-red-700' : 
                          ($item->status == 'pending' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700')) }}">
                        {{ $item->status }}
                    </span>
                </td>
                <td class="p-4 text-center">
                    <!-- Tombol Modal Detail -->
                    <button onclick="openModal('modal-{{ $item->id }}')" class="bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-50 hover:text-blue-600 transition">
                        <i class="fa-solid fa-eye"></i> Detail
                    </button>
                </td>
            </tr>

            <!-- MODAL DETAIL KHUSUS ADMIN -->
            <div id="modal-{{ $item->id }}" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity opacity-0" id="backdrop-{{ $item->id }}" onclick="closeModal('modal-{{ $item->id }}')"></div>
                
                <!-- Modal Content -->
                <div class="bg-white w-full max-w-lg rounded-2xl p-6 relative z-10 transform scale-95 opacity-0 transition-all duration-300 shadow-2xl" id="content-{{ $item->id }}">
                    <div class="flex justify-between items-start mb-5">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Detail Transaksi #{{ $item->id }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Pengajuan oleh <span class="font-bold">{{ $item->user->name }}</span></p>
                        </div>
                        <button onclick="closeModal('modal-{{ $item->id }}')" class="text-gray-400 hover:text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center transition">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <!-- List Barang -->
                    <div class="bg-gray-50 rounded-xl p-4 mb-4 border border-gray-100">
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Rincian Fasilitas</h4>
                        <div class="space-y-2 max-h-[30vh] overflow-y-auto">
                            @foreach($item->details as $detail)
                            <div class="flex justify-between items-center bg-white p-3 rounded-lg border border-gray-100 shadow-sm">
                                <div>
                                    <p class="text-[10px] font-bold text-blue-500">{{ $detail->asset->category->name ?? 'Kategori' }}</p>
                                    <p class="text-sm font-bold text-gray-800">{{ $detail->asset->name }}</p>
                                </div>
                                <span class="text-xs font-black bg-gray-100 text-gray-600 px-3 py-1 rounded-md">{{ $detail->quantity }}x</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Info Alasan & Catatan -->
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tujuan Peminjaman</p>
                            <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-xl border border-gray-100">{{ $item->reason ?? 'Tidak ada keterangan.' }}</p>
                        </div>
                        
                        @if($item->admin_note)
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Catatan Admin</p>
                            <p class="text-sm text-gray-700 bg-yellow-50 p-3 rounded-xl border border-yellow-100">{{ $item->admin_note }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <tr>
                <td colspan="5" class="p-12 text-center text-gray-400">
                    <div class="flex flex-col items-center justify-center">
                        <i class="fa-solid fa-box-open text-4xl mb-3 text-gray-300"></i>
                        <p class="italic">Data laporan peminjaman belum ada.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t bg-gray-50">
        {{ $history->links() }}
    </div>
</div>

<!-- SCRIPT MODAL -->
<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        const backdrop = document.getElementById(id.replace('modal-', 'backdrop-'));
        const content = document.getElementById(id.replace('modal-', 'content-'));
        
        modal.classList.remove('hidden');
        // Trigger reflow
        void modal.offsetWidth; 
        
        backdrop.classList.remove('opacity-0');
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        const backdrop = document.getElementById(id.replace('modal-', 'backdrop-'));
        const content = document.getElementById(id.replace('modal-', 'content-'));
        
        backdrop.classList.add('opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection