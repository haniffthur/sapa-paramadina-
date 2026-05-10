@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Daftar Penalti & Denda</h2>
            <p class="text-sm text-slate-500 font-medium">Verifikasi pembayaran denda dari mahasiswa.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-4 rounded-2xl mb-6 text-sm font-bold flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-lg"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b">
                    <tr class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                        <th class="p-5 pl-8">Mahasiswa</th>
                        <th class="p-5">Alasan Denda</th>
                        <th class="p-5">Jumlah Tagihan</th>
                        <th class="p-5">Bukti Bayar</th>
                        <th class="p-5">Status</th>
                        <th class="p-5 pr-8 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($penalties as $p)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-5 pl-8">
                            <span class="font-bold text-slate-800 text-sm">{{ $p->user->name ?? 'User Dihapus' }}</span>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">{{ $p->user->nim ?? '-' }}</p>
                        </td>
                        <td class="p-5">
                            <p class="text-xs text-slate-500 font-medium line-clamp-1 italic">"{{ $p->description }}"</p>
                        </td>
                        <td class="p-5 font-black text-red-600 text-sm">
                            Rp {{ number_format($p->amount, 0, ',', '.') }}
                        </td>
                       <td class="p-5">
    @if($p->payment_proof)
        {{-- Tombol Lihat Bukti --}}
        <button onclick="showReceipt('{{ asset('storage/' . $p->payment_proof) }}')" 
                class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border border-blue-100 hover:bg-blue-100 transition shadow-sm">
            <i class="fa-solid fa-image mr-1"></i> Cek Bukti
        </button>
    @else
        <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest italic">Belum Upload</span>
    @endif
</td>
                        <td class="p-5">
                            <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border 
                                {{ $p->status == 'paid' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 
                                   ($p->status == 'pending' ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-red-50 text-red-600 border-red-100') }}">
                                {{ $p->status }}
                            </span>
                        </td>
                        <td class="p-5 pr-8 text-right">
                            @if($p->status != 'paid')
                                <form action="{{ route('admin.penalties.paid', $p->id) }}" method="POST">
                                    @csrf
                                    <button class="bg-blue-900 text-white px-4 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-900/20 active:scale-95 transition-all">
                                        Lunaskan
                                    </button>
                                </form>
                            @else
                                <i class="fa-solid fa-circle-check text-slate-200 text-xl"></i>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-20 text-center text-slate-400 font-bold uppercase text-xs tracking-widest">Tidak ada data denda.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="receiptModal" class="fixed inset-0 z-[999] hidden flex items-center justify-center p-6">
    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="closeReceipt()"></div>
    <div class="relative bg-white rounded-[2.5rem] max-w-lg w-full overflow-hidden shadow-2xl transform transition-all scale-95 opacity-0" id="receiptContent">
        <div class="p-6 border-b flex justify-between items-center bg-white">
            <h3 class="font-black text-slate-800 uppercase tracking-tight text-sm">Verifikasi Pembayaran</h3>
            <button onclick="closeReceipt()" class="w-8 h-8 bg-slate-100 text-slate-500 rounded-full flex items-center justify-center hover:bg-slate-200">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="p-4 bg-slate-50">
            <div class="rounded-2xl overflow-hidden shadow-inner border border-slate-200 bg-white">
                <img id="receiptImage" src="" alt="Bukti Bayar" class="w-full h-auto max-h-[60vh] object-contain mx-auto">
            </div>
        </div>
        <div class="p-6 bg-white text-center">
            <p class="text-[10px] font-bold text-slate-400 uppercase">Periksa kecocokan nominal transfer.</p>
        </div>
    </div>
</div>

<script>
    function showReceipt(url) {
        const modal = document.getElementById('receiptModal');
        const content = document.getElementById('receiptContent');
        const img = document.getElementById('receiptImage');
        
        img.src = url;
        modal.classList.remove('hidden');
        
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeReceipt() {
        const modal = document.getElementById('receiptModal');
        const content = document.getElementById('receiptContent');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }
</script>
@endsection