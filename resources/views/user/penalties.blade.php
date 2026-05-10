@extends('layouts.app')

@section('content')
<div class="px-5 py-6 space-y-6">
    
    <!-- Header Navigasi -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center text-slate-400 shadow-sm border border-slate-100 active:scale-90 transition-all">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight leading-none">Tagihan Denda</h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Kewajiban Administrasi</p>
        </div>
    </div>

    <!-- Summary Box -->
    @if($penalties->where('status', 'unpaid')->count() > 0)
    <div class="bg-red-500 rounded-[2rem] p-6 shadow-xl shadow-red-200 relative overflow-hidden">
        <!-- Dekorasi Background -->
        <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-red-200 uppercase tracking-widest mb-1">Total Belum Dibayar</p>
                <h3 class="text-3xl font-black text-white leading-none">
                    Rp {{ number_format($penalties->where('status', 'unpaid')->sum('amount'), 0, ',', '.') }}
                </h3>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="fa-solid fa-triangle-exclamation text-white text-xl"></i>
            </div>
        </div>
    </div>
    @endif

    <!-- List Denda -->
    <div class="space-y-4 pt-4">
       @forelse($penalties as $penalty)
<div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm flex items-center justify-between mb-4">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center 
            {{ $penalty->status == 'unpaid' ? 'bg-red-50 text-red-500' : ($penalty->status == 'pending' ? 'bg-amber-50 text-amber-500' : 'bg-green-50 text-green-500') }}">
            <i class="fa-solid {{ $penalty->status == 'unpaid' ? 'fa-receipt' : ($penalty->status == 'pending' ? 'fa-clock' : 'fa-check-double') }} text-lg"></i>
        </div>
        <div>
            <h4 class="font-extrabold text-slate-800 text-base leading-none mb-1">Rp {{ number_format($penalty->amount, 0, ',', '.') }}</h4>
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($penalty->created_at)->format('d M Y') }}</p>
        </div>
    </div>

    <div>
        @if($penalty->status == 'unpaid')
            <button onclick="openPayModal({{ $penalty->id }}, {{ $penalty->amount }})" class="bg-red-500 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-red-200 active:scale-90 transition-all">
                Bayar
            </button>
        @elseif($penalty->status == 'pending')
            <span class="bg-amber-50 text-amber-600 px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border border-amber-100 italic">Verifikasi</span>
        @else
            <span class="bg-green-50 text-green-600 px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border border-green-100 font-black">Lunas</span>
        @endif
    </div>
</div>
@empty
        <!-- Empty State Denda -->
        <div class="py-20 flex flex-col items-center justify-center text-center">
            <div class="w-24 h-24 bg-white rounded-[2.5rem] flex items-center justify-center shadow-sm border border-slate-50 mb-4 relative">
                <i class="fa-solid fa-shield-halved text-4xl text-green-400"></i>
                <div class="absolute top-0 right-0 w-6 h-6 bg-green-100 rounded-full flex items-center justify-center translate-x-1 -translate-y-1">
                    <i class="fa-solid fa-check text-green-600 text-[10px]"></i>
                </div>
            </div>
            <h3 class="text-sm font-black text-slate-800">Aman Terkendali!</h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Lo nggak punya tagihan denda apapun.</p>
        </div>
        @endforelse
    </div>

</div>

<div id="payModal" class="fixed inset-0 z-[200] hidden flex items-end justify-center p-0">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closePayModal()"></div>
    <div class="bg-white w-full max-w-md rounded-t-[2.5rem] p-8 relative z-10 transform translate-y-full transition-transform duration-300 shadow-2xl">
        <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-8"></div>
        
        <h3 class="text-xl font-black text-slate-800 mb-2 tracking-tight">Bayar Denda</h3>
        <p class="text-[11px] text-slate-500 mb-6 font-medium leading-relaxed">
            Silahkan transfer ke rekening <b class="text-slate-800 italic">Bank Mandiri 123-000-456-789</b> a/n <b class="text-brand">SAPAPARAMADINA</b> sebesar <span id="displayAmount" class="font-black text-red-500"></span>
        </p>

        <form id="payForm" action="" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="bg-slate-50 p-5 rounded-[1.5rem] border border-slate-100">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Upload Bukti Transfer</label>
                <input type="file" name="payment_proof" accept="image/*" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition-all cursor-pointer">
            </div>

            <button type="submit" class="w-full bg-blue-900 text-white font-black py-5 rounded-[1.5rem] uppercase tracking-widest text-[11px] shadow-xl shadow-blue-900/20 active:scale-95 transition-all">
                Kirim Bukti Pembayaran
            </button>
        </form>
    </div>
</div>

<script>
    function openPayModal(id, amount) {
        const modal = document.getElementById('payModal');
        const content = modal.querySelector('.bg-white');
        const form = document.getElementById('payForm');
        const display = document.getElementById('displayAmount');

        display.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        form.action = `/penalties/${id}/pay`; // Sesuai route yang kita buat tadi
        
        modal.classList.remove('hidden');
        setTimeout(() => content.classList.remove('translate-y-full'), 10);
    }

    function closePayModal() {
        const modal = document.getElementById('payModal');
        const content = modal.querySelector('.bg-white');
        content.classList.add('translate-y-full');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }
</script>
@endsection