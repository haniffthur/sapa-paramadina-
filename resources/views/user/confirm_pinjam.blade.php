@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto px-6 py-8">
    <div class="flex items-center gap-4 mb-8">
        <a href="javascript:history.back()" class="w-12 h-12 bg-white border border-slate-100 rounded-2xl flex items-center justify-center shadow-sm text-slate-400 active:scale-90 transition">
            <i class="fa-solid fa-chevron-left text-sm"></i>
        </a>
        <h2 class="text-xl font-black text-slate-800">Detail Peminjaman</h2>
    </div>

    <!-- Asset Card Hero -->
    <div class="bg-white p-2 rounded-[2.5rem] border border-slate-100 shadow-sm mb-8">
        <div class="flex items-center gap-5 p-4">
            <div class="w-20 h-20 bg-slate-50 rounded-[1.8rem] flex items-center justify-center text-brand text-2xl overflow-hidden shrink-0 border border-slate-100">
                @if($asset->image)
                    <img src="{{ asset('storage/' . $asset->image) }}" class="w-full h-full object-cover">
                @else
                    <i class="fa-solid fa-cube opacity-20"></i>
                @endif
            </div>
            <div>
                <span class="bg-blue-50 text-brand text-[9px] font-black uppercase px-2 py-1 rounded-lg border border-blue-100 mb-2 inline-block">{{ $asset->category->name }}</span>
                <h3 class="text-xl font-black text-slate-800 leading-none">{{ $asset->name }}</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase mt-2 tracking-widest">
                    <i class="fa-solid fa-location-dot mr-1"></i> {{ $asset->room->name }}
                </p>
            </div>
        </div>
    </div>

    <form action="{{ route('peminjaman.store') }}" method="POST" class="space-y-8">
        @csrf
        <input type="hidden" name="asset_id" value="{{ $asset->id }}">

        <!-- Duration Picker -->
        <div>
            <div class="flex justify-between items-end mb-4 px-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Durasi Penggunaan</label>
                <span id="duration-display" class="text-brand font-black text-sm">2.0 Jam</span>
            </div>
            
            <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <!-- Buttons -->
                <div class="grid grid-cols-4 gap-3 mb-6">
                    @foreach([1, 2, 4, 8] as $hour)
                    <button type="button" onclick="setDuration({{ $hour }})" class="preset-btn py-3 rounded-2xl text-[10px] font-black uppercase transition-all bg-slate-50 text-slate-400 border border-slate-100 hover:border-brand">
                        {{ $hour }}h
                    </button>
                    @endforeach
                </div>
                
                <input type="range" name="duration" id="duration-slider" min="1" max="12" step="0.5" value="2" 
                    class="w-full h-1.5 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-brand"
                    oninput="updateDisplay(this.value)">
                
                <div class="flex justify-between mt-3 px-1">
                    <span class="text-[9px] font-black text-slate-300">1H</span>
                    <span class="text-[9px] font-black text-slate-300">12H</span>
                </div>
            </div>
        </div>

        <!-- Masukkan ini di bawah Durasi Pinjam -->
<div class="mb-6">
    <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest ml-2 mb-2 block">Jumlah Unit</label>
    <div class="bg-white p-4 rounded-[2rem] border border-slate-100 flex items-center justify-between">
        <button type="button" onclick="decrementQty()" class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400">-</button>
        <input type="number" name="qty_pinjam" id="qty_input" value="1" min="1" max="{{ $asset->quantity }}" 
            class="bg-transparent text-center font-black text-xl w-20 outline-none" readonly>
        <button type="button" onclick="incrementQty()" class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400">+</button>
    </div>
    <p class="text-[10px] text-slate-400 mt-2 ml-4 font-bold italic text-right">Tersedia: {{ $asset->quantity }} Unit</p>
</div>

<script>
    function incrementQty() {
        let input = document.getElementById('qty_input');
        let max = {{ $asset->quantity }};
        if(parseInt(input.value) < max) input.value = parseInt(input.value) + 1;
    }
    function decrementQty() {
        let input = document.getElementById('qty_input');
        if(parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
    }
</script>

        <!-- Reason Input -->
        <div>
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2 block">Keterangan / Keperluan</label>
            <textarea name="reason" rows="3" class="w-full bg-white px-6 py-5 rounded-[2rem] border border-slate-100 focus:ring-4 focus:ring-blue-50 focus:border-brand outline-none transition-all text-sm font-medium text-slate-600" placeholder="Misal: Revisi Final Project..." required></textarea>
        </div>

        <button type="submit" class="w-full bg-brand text-white py-6 rounded-[2.2rem] font-black uppercase tracking-[0.15em] shadow-2xl shadow-blue-900/20 active:scale-[0.98] transition-all border-b-4 border-blue-950">
            Kirim Permintaan
        </button>
    </form>
</div>

<script>
    function updateDisplay(val) {
        document.getElementById('duration-display').innerText = parseFloat(val).toFixed(1) + " Jam";
        document.querySelectorAll('.preset-btn').forEach(btn => {
            btn.classList.replace('bg-brand', 'bg-slate-50');
            btn.classList.replace('text-white', 'text-slate-400');
            btn.classList.remove('border-brand');
        });
    }

    function setDuration(val) {
        const slider = document.getElementById('duration-slider');
        slider.value = val;
        updateDisplay(val);
        event.target.classList.replace('bg-slate-50', 'bg-brand');
        event.target.classList.replace('text-slate-400', 'text-white');
        event.target.classList.add('border-brand');
    }
</script>
@endsection