@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto px-6 py-8 pb-32">
    <div class="flex items-center gap-4 mb-8">
        <a href="javascript:history.back()" class="w-12 h-12 bg-white border border-slate-100 rounded-2xl flex items-center justify-center shadow-sm text-slate-400 active:scale-90 transition">
            <i class="fa-solid fa-chevron-left text-sm"></i>
        </a>
        <h2 class="text-xl font-black text-slate-800">Konfirmasi Pinjam</h2>
    </div>

    <div class="bg-white p-2 rounded-[2.5rem] border border-slate-100 shadow-sm mb-8">
        <div class="flex items-center gap-5 p-4">
            <div class="w-20 h-20 bg-slate-50 rounded-[1.8rem] flex items-center justify-center text-brand text-2xl overflow-hidden shrink-0 border border-slate-100">
                @if($asset->image)
                    <img src="{{ asset('storage/' . $asset->image) }}" class="w-full h-full object-cover">
                @else
                    <div class="flex flex-col items-center">
                        <i class="fa-solid fa-cube opacity-20 text-3xl"></i>
                        <span class="text-[8px] font-black uppercase text-slate-300 mt-1">No Image</span>
                    </div>
                @endif
            </div>
            <div>
                <span class="bg-blue-50 text-brand text-[9px] font-black uppercase px-2 py-1 rounded-lg border border-blue-100 mb-2 inline-block">
                    {{ $asset->categories->name ?? 'Umum' }}
                </span>
                <h3 class="text-xl font-black text-slate-800 leading-none">{{ $asset->name }}</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase mt-2 tracking-widest">
                    <i class="fa-solid fa-location-dot mr-1"></i> {{ $asset->room->name ?? 'Lokasi Tidak Set' }}
                </p>
            </div>
        </div>
    </div>

    <form action="{{ route('peminjaman.store') }}" method="POST" class="space-y-8">
        @csrf
        <input type="hidden" name="asset_id" value="{{ $asset->id }}">

        <div>
            <div class="flex justify-between items-end mb-4 px-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Durasi Penggunaan</label>
                <span id="duration-display" class="text-brand font-black text-sm">2.0 Jam</span>
            </div>
            
            <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <div class="grid grid-cols-4 gap-3 mb-6">
                    @foreach([1, 2, 4, 8] as $hour)
                    <button type="button" onclick="setDuration({{ $hour }}, this)" 
                        class="preset-btn py-3 rounded-2xl text-[10px] font-black uppercase transition-all {{ $hour == 2 ? 'bg-brand text-white border-brand' : 'bg-slate-50 text-slate-400 border-slate-100' }} border">
                        {{ $hour }}h
                    </button>
                    @endforeach
                </div>
                
                <input type="range" name="duration" id="duration-slider" min="1" max="12" step="0.5" value="2" 
                    class="w-full h-1.5 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-brand"
                    oninput="updateDisplay(this.value)">
                
                <div class="flex justify-between mt-4 px-1 items-center">
                    <span class="text-[9px] font-black text-slate-300 uppercase">Estimasi Kembali:</span>
                    <span id="return-time" class="text-[11px] font-black text-emerald-500 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100 uppercase">
                        --:--
                    </span>
                </div>
            </div>
        </div>

        <div>
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-3 block">Jumlah Unit</label>
            <div class="bg-white p-4 rounded-[2rem] border border-slate-100 flex items-center justify-between shadow-sm">
                <button type="button" onclick="decrementQty()" class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-800 font-bold active:bg-slate-200 transition">-</button>
                <div class="text-center">
                    <input type="number" name="qty_pinjam" id="qty_input" value="1" min="1" max="{{ $asset->quantity }}" 
                        class="bg-transparent text-center font-black text-2xl w-20 outline-none" readonly>
                    <p class="text-[8px] text-slate-300 font-black uppercase tracking-tighter mt-1">Stok: {{ $asset->quantity }}</p>
                </div>
                <button type="button" onclick="incrementQty()" class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-800 font-bold active:bg-slate-200 transition">+</button>
            </div>
        </div>

        <div>
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2 block">Keterangan / Keperluan</label>
            <textarea name="reason" rows="3" 
                class="w-full bg-white px-6 py-5 rounded-[2rem] border border-slate-100 focus:ring-4 focus:ring-blue-50 focus:border-brand outline-none transition-all text-sm font-medium text-slate-600 shadow-sm" 
                placeholder="Misal: Untuk kebutuhan praktik lab..." required></textarea>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-brand text-white py-6 rounded-[2.2rem] font-black uppercase tracking-[0.15em] shadow-2xl shadow-blue-900/20 active:scale-[0.98] transition-all border-b-4 border-blue-950">
                Kirim Permintaan
            </button>
            <p class="text-[9px] text-center text-slate-400 mt-4 font-medium px-6 leading-relaxed">
                Dengan menekan tombol di atas, lu setuju untuk menjaga aset ini dan mengembalikannya tepat waktu.
            </p>
        </div>
    </form>
</div>

<script>
    // Logic Quantity
    function incrementQty() {
        let input = document.getElementById('qty_input');
        let max = {{ $asset->quantity }};
        if(parseInt(input.value) < max) input.value = parseInt(input.value) + 1;
    }
    
    function decrementQty() {
        let input = document.getElementById('qty_input');
        if(parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
    }

    // Logic Duration & Return Time Estimation
    function updateDisplay(val) {
        document.getElementById('duration-display').innerText = parseFloat(val).toFixed(1) + " Jam";
        
        // Reset preset buttons style
        document.querySelectorAll('.preset-btn').forEach(btn => {
            btn.classList.remove('bg-brand', 'text-white', 'border-brand');
            btn.classList.add('bg-slate-50', 'text-slate-400', 'border-slate-100');
        });

        // Calculate Return Time
        calculateReturnTime(val);
    }

    function setDuration(val, btn) {
        const slider = document.getElementById('duration-slider');
        slider.value = val;
        updateDisplay(val);
        
        // Active style
        btn.classList.remove('bg-slate-50', 'text-slate-400', 'border-slate-100');
        btn.classList.add('bg-brand', 'text-white', 'border-brand');
    }

    function calculateReturnTime(hours) {
        const now = new Date();
        const returnDate = new Date(now.getTime() + (hours * 60 * 60 * 1000));
        
        const hoursStr = returnDate.getHours().toString().padStart(2, '0');
        const minsStr = returnDate.getMinutes().toString().padStart(2, '0');
        
        document.getElementById('return-time').innerText = hoursStr + ":" + minsStr + " WIB";
    }

    // Initialize on load
    window.onload = function() {
        calculateReturnTime(2);
    };
</script>

<style>
    /* Slider Styling */
    #duration-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 24px;
        height: 24px;
        background: #1e3a8a;
        border: 4px solid #fff;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    
    /* Scroll Behavior Fix */
    body {
        overscroll-behavior-y: contain;
    }
</style>
@endsection