@extends('layouts.app')

@section('content')
<form action="{{ route('peminjaman.storeMulti') }}" method="POST" id="borrowForm">
    @csrf
    <input type="hidden" name="room_id" value="{{ $room->id }}">
    
    <div class="px-5 py-6 space-y-6 pb-40">
        
        <!-- Header / Hero Section Khusus Ruangan -->
        <div class="relative rounded-[2.5rem] overflow-hidden aspect-[4/3] shadow-2xl shadow-blue-900/10 border-4 border-white">
            <img src="{{ asset('img/univ.jpg') }}" class="w-full h-full object-cover grayscale-[0.2] brightness-75">
            <div class="absolute inset-0 bg-gradient-to-t from-brand/95 via-brand/40 to-transparent"></div>
            
            <a href="{{ route('dashboard') }}" class="absolute top-6 left-6 w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white backdrop-blur-md active:scale-90 transition-all border border-white/30">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>

            <div class="absolute bottom-6 left-6 right-6">
                <p class="text-[10px] font-bold text-blue-200 uppercase tracking-[0.3em] mb-1">{{ $room->prodi->nama_prodi ?? 'Fasilitas Kampus' }}</p>
                <h1 class="text-3xl font-black text-white leading-tight">{{ $room->name }}</h1>
            </div>
        </div>

        <!-- ALERT ERROR (BENTROK / VALIDASI) -->
        @if(session('error') || $errors->any())
        <div class="bg-red-50 border border-red-200 p-4 rounded-2xl flex items-start gap-3 shadow-sm">
            <div class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-slate-800">Pengajuan Gagal</h4>
                <p class="text-xs font-medium text-slate-500 mt-0.5">
                    {{ session('error') }}
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </p>
            </div>
        </div>
        @endif

        <!-- Search & Filter Kategori -->
        <div class="space-y-4">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" id="searchInput" placeholder="Cari fasilitas di ruangan ini..." class="w-full bg-white border border-slate-100 rounded-2xl py-3.5 pl-11 pr-4 text-sm font-medium focus:ring-4 focus:ring-blue-50 outline-none transition-all shadow-sm">
            </div>

            <!-- Extract kategori unik dari aset yang ada di ruangan ini dengan aman -->
            @php 
                $roomCategories = $room->assets->map->category->filter()->unique('id'); 
            @endphp
            
            <div class="flex gap-2 overflow-x-auto pb-2 -mx-5 px-5 no-scrollbar">
                <button type="button" class="filter-btn shrink-0 bg-brand text-white px-6 py-2.5 rounded-xl text-xs font-black shadow-lg shadow-blue-200 transition-all" data-filter="all">Semua</button>
                @foreach($roomCategories as $cat)
                    <button type="button" class="filter-btn shrink-0 bg-white text-slate-400 border border-slate-100 px-6 py-2.5 rounded-xl text-xs font-bold hover:text-brand transition-all" data-filter="{{ $cat->id }}">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Katalog Asset Spesifik Ruangan Ini -->
        <section class="space-y-4">
            <div class="space-y-3" id="assetContainer">
                @forelse($room->assets as $asset)
                <div class="asset-card bg-white p-4 rounded-3xl border border-slate-100 flex items-center justify-between shadow-sm transition-all duration-300" 
                     data-category="{{ $asset->category_id }}" 
                     data-name="{{ strtolower($asset->name) }}">
                     
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-[1.2rem] overflow-hidden bg-slate-50 border border-slate-100 shrink-0 relative">
                            @if($asset->image)
                                <img src="{{ asset('storage/' . $asset->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-200"><i class="fa-solid fa-image text-xl"></i></div>
                            @endif
                        </div>
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-800 leading-tight mb-1">{{ $asset->name }}</h4>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $asset->category->name ?? 'Umum' }}</p>
                            <p class="text-[10px] font-black {{ $asset->quantity > 0 ? 'text-green-500' : 'text-red-500' }}">Sisa: {{ $asset->quantity }} Unit</p>
                        </div>
                    </div>

                    <!-- Input Qty Multi-Select -->
                    @if($asset->quantity > 0)
                    <div class="flex items-center bg-slate-50 border border-slate-100 rounded-2xl p-1">
                        <button type="button" onclick="updateQty({{ $asset->id }}, -1)" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-brand active:scale-90 transition-all"><i class="fa-solid fa-minus text-xs"></i></button>
                        
                        <input type="number" name="items[{{ $asset->id }}]" id="qty_{{ $asset->id }}" data-name="{{ $asset->name }}" value="0" min="0" max="{{ $asset->quantity }}" class="w-6 bg-transparent text-center text-xs font-black text-slate-800 outline-none pointer-events-none" readonly>
                        
                        <button type="button" onclick="updateQty({{ $asset->id }}, 1, {{ $asset->quantity }})" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-brand active:scale-90 transition-all"><i class="fa-solid fa-plus text-xs"></i></button>
                    </div>
                    @else
                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest bg-slate-50 px-3 py-2 rounded-xl">Habis</span>
                    @endif
                </div>
                @empty
                <div class="bg-white py-12 rounded-[2.5rem] border border-dashed border-slate-200 text-center flex flex-col items-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3"><i class="fa-solid fa-ghost text-2xl text-slate-300"></i></div>
                    <h4 class="text-sm font-black text-slate-800">Ruangan Kosong</h4>
                </div>
                @endforelse
            </div>
            
            <!-- Empty State untuk Filter Pencarian -->
            <div id="emptyState" class="hidden py-12 flex-col items-center justify-center text-center bg-white rounded-[2.5rem] border border-slate-100 shadow-sm">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                    <i class="fa-solid fa-box-open text-2xl text-slate-300"></i>
                </div>
                <h4 class="text-sm font-black text-slate-800">Tidak Ditemukan</h4>
            </div>
        </section>
    </div>

    <!-- Floating Action Button (Membuka Modal) -->
    <div id="floating-cart" class="fixed bottom-28 left-0 right-0 px-5 z-[40] transition-all duration-300 translate-y-32 opacity-0 pointer-events-none">
        <button type="button" onclick="openModal()" class="w-full bg-brand text-white p-4 rounded-[1.5rem] shadow-[0_15px_30px_rgba(30,58,138,0.3)] flex items-center justify-between group pointer-events-auto active:scale-95 transition-transform">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fa-solid fa-basket-shopping text-white"></i>
                </div>
                <div class="text-left">
                    <p class="text-[10px] font-bold text-blue-200 uppercase tracking-widest leading-none mb-1">Total Dipilih</p>
                    <p class="text-sm font-black leading-none"><span id="total-items">0</span> Barang</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-[11px] font-black uppercase tracking-widest">
                Lanjut Pinjam <i class="fa-solid fa-chevron-up group-hover:-translate-y-1 transition-transform"></i>
            </div>
        </button>
    </div>

    <!-- Modal Checkout (Bottom Sheet Style) -->
   <div id="checkoutModal" class="fixed inset-0 z-[100] hidden items-end justify-center pointer-events-none">
        <div id="modalBackdrop" onclick="closeModal()" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm opacity-0 transition-opacity duration-300 pointer-events-auto"></div>
        
        <div id="modalContent" class="bg-white w-full max-w-md rounded-t-[2.5rem] p-6 pb-12 transform translate-y-full transition-transform duration-300 pointer-events-auto relative shadow-2xl">
            <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-6"></div>
            
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-black text-slate-800 leading-tight">Detail Peminjaman</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Lengkapi data untuk admin</p>
                </div>
                <button type="button" onclick="closeModal()" class="w-8 h-8 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center active:scale-90 transition-all">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- TAMPILAN BARANG YANG DIPILIH -->
            <div class="mb-5">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Fasilitas Dipilih</label>
                <div id="selectedItemsList" class="space-y-2 max-h-[20vh] overflow-y-auto pr-1">
                    <!-- List barang akan masuk otomatis lewat JavaScript -->
                </div>
            </div>

            <!-- BARU: TOGGLE TIPE PEMINJAMAN -->
            <div class="bg-slate-50 p-1.5 rounded-2xl flex items-center mb-5 border border-slate-100">
                <button type="button" id="btn-type-now" onclick="setLoanType('now')" class="flex-1 py-2.5 text-xs font-black rounded-xl bg-brand text-white shadow-md transition-all">Pinjam Sekarang</button>
                <button type="button" id="btn-type-book" onclick="setLoanType('book')" class="flex-1 py-2.5 text-xs font-bold rounded-xl text-slate-400 bg-transparent hover:text-slate-600 transition-all">Booking Nanti</button>
            </div>

            <!-- FORM INPUT WAKTU & DURASI -->
            <div id="form-grid" class="grid grid-cols-1 gap-2 mb-4 transition-all">
                
                <!-- Input Durasi (Selalu Tampil) -->
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Durasi (Jam)</label>
                    <div class="relative">
                        <input type="number" name="duration" min="1" max="24" value="2" class="w-full h-[60px] bg-slate-50 border border-slate-100 rounded-2xl pl-10 pr-4 text-sm font-black text-slate-800 outline-none focus:ring-2 focus:ring-brand" required>
                        <i class="fa-regular fa-clock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <!-- Input Kapan (Hidden by Default) -->
                <div id="bookingField" class="hidden">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Hari/Tanggal</label>
                    <div class="relative">
                        <input type="datetime-local" name="booking_start" id="booking_start" class="w-full h-[60px] bg-slate-50 border border-slate-100 rounded-2xl pl-10 pr-4 text-sm font-black text-slate-800 outline-none appearance-none focus:ring-2 focus:ring-brand">
                        <i class="fa-regular fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

            </div>
            
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Alasan Peminjaman</label>
            <textarea name="reason" rows="3" class="w-full bg-slate-50 border border-slate-100 rounded-2xl p-4 text-sm font-bold text-slate-800 outline-none mb-6 focus:ring-2 focus:ring-brand" placeholder="Misal: Untuk praktikum jaringan komputer..." required></textarea>

            <button type="submit" class="w-full bg-brand text-white p-4 rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-200 active:scale-95 transition-transform flex items-center justify-center gap-2">
                <i class="fa-solid fa-paper-plane text-sm"></i> Kirim Permintaan
            </button>
        </div>
    </div>
</form>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
    // --- 0. LOGIC TOGGLE BOOKING / SEKARANG ---
    function setLoanType(type) {
        const btnNow = document.getElementById('btn-type-now');
        const btnBook = document.getElementById('btn-type-book');
        const formGrid = document.getElementById('form-grid');
        const bookingField = document.getElementById('bookingField');
        const bookingInput = document.getElementById('booking_start');

        if(type === 'now') {
            btnNow.className = 'flex-1 py-2.5 text-xs font-black rounded-xl bg-brand text-white shadow-md transition-all';
            btnBook.className = 'flex-1 py-2.5 text-xs font-bold rounded-xl text-slate-400 bg-transparent hover:text-slate-600 transition-all';
            
            formGrid.classList.remove('grid-cols-2');
            formGrid.classList.add('grid-cols-1');
            bookingField.classList.add('hidden');
            
            // AMAN 1: Matikan input kalender biar nggak ikut dikirim ke server
            bookingInput.value = ''; 
            bookingInput.removeAttribute('required');
            bookingInput.disabled = true; 
        } else {
            btnBook.className = 'flex-1 py-2.5 text-xs font-black rounded-xl bg-brand text-white shadow-md transition-all';
            btnNow.className = 'flex-1 py-2.5 text-xs font-bold rounded-xl text-slate-400 bg-transparent hover:text-slate-600 transition-all';
            
            formGrid.classList.remove('grid-cols-1');
            formGrid.classList.add('grid-cols-2');
            bookingField.classList.remove('hidden');
            
            // AMAN 2: Nyalakan input dan wajib diisi
            bookingInput.setAttribute('required', 'required'); 
            bookingInput.disabled = false;
        }
    }

    // --- 1. LOGIC CART (PLUS & MINUS) ---
    let totalItems = 0;
    
    function updateQty(id, change, max = 0) {
        let input = document.getElementById('qty_' + id);
        if(!input) return; // Pengaman

        let currentVal = parseInt(input.value) || 0;
        let newVal = currentVal + change;

        if(newVal >= 0 && (change < 0 || newVal <= max)) {
            input.value = newVal;
            totalItems += change;
            
            document.getElementById('total-items').innerText = totalItems;
            
            let cart = document.getElementById('floating-cart');
            if(totalItems > 0) {
                cart.classList.remove('translate-y-32', 'opacity-0');
            } else {
                cart.classList.add('translate-y-32', 'opacity-0');
            }
        }
    }

    // --- 2. LOGIC BUKA / TUTUP MODAL ---
    function openModal() {
        const modal = document.getElementById('checkoutModal');
        const backdrop = document.getElementById('modalBackdrop');
        const content = document.getElementById('modalContent');
        const listContainer = document.getElementById('selectedItemsList');
        
        listContainer.innerHTML = '';
        
        const inputs = document.querySelectorAll('input[name^="items["]');
        inputs.forEach(input => {
            const qty = parseInt(input.value) || 0;
            if(qty > 0) {
                const name = input.getAttribute('data-name');
                listContainer.innerHTML += `
                    <div class="flex items-center justify-between bg-slate-50 border border-slate-100 p-3 rounded-xl mb-2">
                        <span class="text-xs font-bold text-slate-800">${name}</span>
                        <span class="text-xs font-black text-brand bg-blue-50 px-2 py-1 rounded-lg">${qty}x</span>
                    </div>
                `;
            }
        });

        // Default ke 'Pinjam Sekarang' saat modal dibuka
        setLoanType('now');

        // AMAN 3: Kunci kalender biar gak bisa pilih tanggal/jam yang udah lewat (Masa Lalu)
        let now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.getElementById('booking_start').min = now.toISOString().slice(0,16);

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            content.classList.remove('translate-y-full');
        }, 10);
    }

    function closeModal() {
        const modal = document.getElementById('checkoutModal');
        const backdrop = document.getElementById('modalBackdrop');
        const content = document.getElementById('modalContent');

        backdrop.classList.add('opacity-0');
        content.classList.add('translate-y-full');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    // --- 3. LOGIC SEARCH & FILTER KATEGORI ---
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const filterBtns = document.querySelectorAll('.filter-btn');
        const assetCards = document.querySelectorAll('.asset-card');
        const emptyState = document.getElementById('emptyState');

        let currentCategory = 'all';
        let searchQuery = '';

        function filterAssets() {
            let visibleCount = 0;

            assetCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                const cardName = card.getAttribute('data-name') || '';

                const matchesSearch = cardName.includes(searchQuery);
                const matchesCategory = (currentCategory === 'all') || (cardCategory === currentCategory);

                if (matchesSearch && matchesCategory) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (emptyState) {
                if (visibleCount === 0) {
                    emptyState.classList.remove('hidden');
                    emptyState.classList.add('flex');
                } else {
                    emptyState.classList.add('hidden');
                    emptyState.classList.remove('flex');
                }
            }
        }

        if(searchInput) {
            searchInput.addEventListener('input', (e) => {
                searchQuery = e.target.value.toLowerCase();
                filterAssets();
            });
        }

        if(filterBtns.length > 0) {
            filterBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    filterBtns.forEach(b => {
                        b.className = 'filter-btn shrink-0 bg-white text-slate-400 border border-slate-100 px-6 py-2.5 rounded-xl text-xs font-bold hover:text-brand transition-all';
                    });
                    const clickedBtn = e.currentTarget;
                    clickedBtn.className = 'filter-btn shrink-0 bg-brand text-white px-6 py-2.5 rounded-xl text-xs font-black shadow-lg shadow-blue-200 transition-all';
                    currentCategory = clickedBtn.getAttribute('data-filter');
                    filterAssets();
                });
            });
        }
    });
</script>
@endsection