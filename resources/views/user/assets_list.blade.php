@extends('layouts.app')

@section('content')
<div class="px-5 py-6 space-y-6 pb-24">
    <!-- Header & Search -->
    <div class="space-y-4">
        <h2 class="text-2xl font-black text-slate-800 tracking-tight">Katalog Asset</h2>
        <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" id="searchInput" placeholder="Cari asset..." class="w-full bg-white border border-slate-100 rounded-2xl py-3.5 pl-11 pr-4 text-sm font-medium focus:ring-4 focus:ring-blue-50 outline-none transition-all">
        </div>
    </div>

    <!-- Filter Kategori (Horizontal Scroll) -->
    <div class="flex gap-2 overflow-x-auto pb-2 -mx-5 px-5 no-scrollbar" id="categoryFilters">
        <button class="filter-btn shrink-0 bg-brand text-white px-6 py-2.5 rounded-xl text-xs font-black shadow-lg shadow-blue-200 transition-all" data-filter="all">Semua</button>
        @foreach($categories as $cat)
            <button class="filter-btn shrink-0 bg-white text-slate-400 border border-slate-100 px-6 py-2.5 rounded-xl text-xs font-bold hover:text-brand transition-all" data-filter="{{ $cat->id }}">
                {{ $cat->name }}
            </button>
        @endforeach
    </div>

    <!-- Asset Grid -->
    <div class="grid grid-cols-2 gap-4" id="assetContainer">
        @foreach($assets as $asset)
       <a href="{{ route('peminjaman.asset.detail', $asset->id) }}" class="asset-card bg-white rounded-[1.5rem] overflow-hidden border border-slate-100 shadow-sm flex flex-col group transition-all duration-300 hover:shadow-md cursor-pointer active:scale-95" 
             data-category="{{ $asset->category_id }}" 
             data-name="{{ strtolower($asset->name) }}">
            
            <!-- Thumbnail -->
            <div class="aspect-square relative overflow-hidden bg-slate-50">
                @if($asset->quantity > 0)
                    <div class="absolute top-3 left-3 bg-blue-900 text-white text-[8px] font-black px-3 py-1 rounded-full z-10 uppercase tracking-widest shadow-md">Tersedia</div>
                @else
                    <div class="absolute top-3 left-3 bg-red-500 text-white text-[8px] font-black px-3 py-1 rounded-full z-10 uppercase tracking-widest shadow-md">Habis</div>
                @endif
                
                @if($asset->image)
                    <img src="{{ asset('storage/' . $asset->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-200 italic text-[10px]">No Image</div>
                @endif
            </div>

            <!-- Detail -->
            <div class="p-4 flex flex-col flex-1 gap-1">
                <h4 class="font-extrabold text-slate-800 text-sm line-clamp-1 leading-tight">{{ $asset->name }}</h4>
                <div class="flex items-center gap-1 text-slate-400 mb-2">
                    <i class="fa-solid fa-location-dot text-[10px]"></i>
                    <span class="text-[9px] font-bold uppercase tracking-tighter">{{ $asset->room->name }}</span>
                </div>
                
                <!-- Info Stok (Tanpa Tombol Action) -->
                <div class="mt-auto pt-2 border-t border-slate-50">
                    <p class="text-[8px] font-bold text-slate-300 uppercase leading-none mb-1">Total Stok Tersisa</p>
                    <p class="text-xs font-black {{ $asset->quantity > 0 ? 'text-brand' : 'text-red-500' }} leading-none">{{ $asset->quantity }} <span class="text-[9px]">Unit</span></p>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <!-- Empty State (Hidden by default) -->
    <div id="emptyState" class="hidden py-16 flex-col items-center justify-center text-center">
        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-50 mb-4">
            <i class="fa-solid fa-box-open text-3xl text-slate-200"></i>
        </div>
        <h3 class="text-sm font-black text-slate-800">Asset tidak ditemukan</h3>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Coba kata kunci atau kategori lain.</p>
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const filterBtns = document.querySelectorAll('.filter-btn');
        const assetCards = document.querySelectorAll('.asset-card');
        const emptyState = document.getElementById('emptyState');

        let currentCategory = 'all';
        let searchQuery = '';

        // Fungsi Utama untuk Filter
        function filterAssets() {
            let visibleCount = 0;

            assetCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                const cardName = card.getAttribute('data-name');

                // Cek apakah match dengan search query & kategori
                const matchesSearch = cardName.includes(searchQuery);
                const matchesCategory = (currentCategory === 'all') || (cardCategory === currentCategory);

                if (matchesSearch && matchesCategory) {
                    card.style.display = 'flex'; // Munculin card
                    visibleCount++;
                } else {
                    card.style.display = 'none'; // Sembunyiin card
                }
            });

            // Tampilkan empty state kalau nggak ada yang cocok
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

        // Event Listener untuk Search Input
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                searchQuery = e.target.value.toLowerCase();
                filterAssets();
            });
        }

        // Event Listener untuk Tombol Kategori
        if (filterBtns.length > 0) {
            filterBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    // 1. Reset class semua tombol jadi "Inactive"
                    filterBtns.forEach(b => {
                        b.className = 'filter-btn shrink-0 bg-white text-slate-400 border border-slate-100 px-6 py-2.5 rounded-xl text-xs font-bold hover:text-brand transition-all';
                    });

                    // 2. Set class tombol yang diklik jadi "Active" (Warna Brand)
                    const clickedBtn = e.currentTarget;
                    clickedBtn.className = 'filter-btn shrink-0 bg-brand text-white px-6 py-2.5 rounded-xl text-xs font-black shadow-lg shadow-blue-200 transition-all';

                    // 3. Update current category & jalankan filter
                    currentCategory = clickedBtn.getAttribute('data-filter');
                    filterAssets();
                });
            });
        }
    });
</script>
@endsection