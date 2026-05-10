@extends('layouts.admin')

@section('content')
<div class="p-8 max-w-4xl">
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Pengaturan Sistem</h2>
        <p class="text-sm text-slate-400 font-medium">Kelola konfigurasi denda dan operasional aplikasi.</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-4 rounded-[2rem] mb-8 text-sm font-bold flex items-center gap-3">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <div class="w-12 h-12 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center mb-6 border border-red-100">
                <i class="fa-solid fa-money-bill-transfer text-xl"></i>
            </div>
            
            <h3 class="text-lg font-black text-slate-800 mb-2">Denda Keterlambatan</h3>
            <p class="text-xs text-slate-400 font-medium mb-6">Setiap mahasiswa yang telat mengembalikan barang akan dikenakan denda otomatis per jam.</p>

            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 mb-2 block">Biaya Per Jam (Rp)</label>
                        <input type="number" name="penalty_per_hour" 
                            value="{{ $penaltyRate->value ?? 5000 }}"
                            class="w-full bg-slate-50 border border-slate-100 px-6 py-4 rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-blue-50 focus:border-brand outline-none transition-all">
                    </div>

                    <button type="submit" class="w-full bg-brand text-white py-4 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl shadow-blue-900/10 active:scale-95 transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-blue-900 p-8 rounded-[2.5rem] text-white shadow-2xl shadow-blue-900/20 flex flex-col justify-between">
            <div>
                <i class="fa-solid fa-circle-info text-3xl opacity-50 mb-6"></i>
                <h3 class="text-xl font-black mb-4 leading-tight">Sistem Denda Otomatis</h3>
                <p class="text-sm text-blue-100 leading-relaxed font-medium">
                    Denda dihitung berdasarkan selisih waktu asli pengembalian dengan estimasi waktu kembali yang diinput mahasiswa saat meminjam.
                </p>
            </div>
            
            <div class="pt-8 border-t border-white/10 mt-8">
                <span class="text-[10px] font-black uppercase tracking-widest opacity-50">Status Fitur</span>
                <div class="flex items-center gap-2 mt-2">
                    <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                    <span class="text-xs font-bold uppercase tracking-tight">Aktif & Real-time</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection