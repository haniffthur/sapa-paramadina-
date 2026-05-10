@extends('layouts.admin')

@section('content')
<div class="p-6 pb-24">
    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-800 tracking-tight">Laporan Kerusakan Aset</h2>
        <p class="text-sm text-slate-500 mt-1 font-medium">Review laporan dari Petugas dan eksekusi denda otomatis ke mahasiswa.</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-4 rounded-2xl mb-6 text-sm font-bold flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-lg"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-100 text-red-600 px-6 py-4 rounded-2xl mb-6 text-sm font-bold flex items-center gap-3">
            <i class="fa-solid fa-triangle-exclamation text-lg"></i> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                        <th class="p-5 pl-8">Waktu & Pelapor</th>
                        <th class="p-5">Mahasiswa (Peminjam)</th>
                        <th class="p-5">Aset & Kerusakan</th>
                        <th class="p-5">Usulan Denda</th>
                        <th class="p-5">Status</th>
                        <th class="p-5 pr-8 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($reports as $report)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-5 pl-8">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-800">{{ $report->petugas->name ?? 'Petugas' }}</span>
                                <span class="text-[10px] font-bold text-slate-400 mt-1 uppercase">{{ $report->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        </td>

                        <td class="p-5">
                            @if($report->peminjaman && $report->peminjaman->user)
                                <p class="text-sm font-bold text-slate-800 leading-tight">{{ $report->peminjaman->user->name }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase mt-0.5">NIM: {{ $report->peminjaman->user->nim ?? '-' }}</p>
                            @else
                                <p class="text-sm font-bold text-red-500 italic">Data Pinjam Hilang</p>
                            @endif
                        </td>

                        <td class="p-5">
                            <p class="text-xs font-bold text-slate-700">{{ $report->asset->name ?? 'Aset' }}</p>
                            <p class="text-[11px] font-medium text-slate-500 mt-1 italic">"{{ $report->deskripsi_kerusakan }}"</p>
                            @if($report->foto_kerusakan)
                                <a href="{{ asset('storage/' . $report->foto_kerusakan) }}" target="_blank" class="inline-flex items-center gap-1.5 mt-2 text-[9px] font-black text-blue-600 bg-blue-50 px-2 py-1 rounded-lg border border-blue-100 uppercase">
                                    <i class="fa-solid fa-image"></i> Bukti Foto
                                </a>
                            @endif
                        </td>

                        <td class="p-5">
                            @if($report->nominal_denda > 0)
                                <span class="text-sm font-black text-red-600">Rp {{ number_format($report->nominal_denda, 0, ',', '.') }}</span>
                            @else
                                <span class="text-[10px] font-bold text-slate-300 uppercase">Tanpa Usulan</span>
                            @endif
                        </td>

                        <td class="p-5">
                            <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $report->status == 'menunggu' ? 'bg-orange-50 text-orange-500 border border-orange-100' : 'bg-emerald-50 text-emerald-600 border border-emerald-100' }}">
                                {{ $report->status }}
                            </span>
                        </td>

                        <td class="p-5 pr-8 text-right">
                            @if($report->status == 'menunggu' && $report->peminjaman)
                                <button onclick="bukaModalDenda({{ $report->id }}, '{{ addslashes($report->asset->name ?? 'Aset') }}', '{{ addslashes($report->peminjaman->user->name ?? 'User') }}', {{ $report->nominal_denda ?? 0 }})" 
                                    class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest transition shadow-lg shadow-blue-900/20 active:scale-95">
                                    Eksekusi
                                </button>
                            @else
                                <i class="fa-solid fa-circle-check text-slate-200 text-xl"></i>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-20 text-center">
                            <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest">Aset Aman Terkendali</h4>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-slate-50 bg-slate-50/30">
            {{ $reports->links() }}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function bukaModalDenda(id, asset, user, nominal) {
        Swal.fire({
            title: 'Review Denda',
            html: `
                <div class="text-left bg-slate-50 p-4 rounded-2xl mb-4 border border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase">Informasi</p>
                    <p class="text-xs font-bold text-slate-800 mt-1">${asset} - <span class="text-red-600">${user}</span></p>
                </div>
                <div class="text-left ml-1 mb-2 text-[10px] font-black text-slate-400 uppercase">Set Nominal Denda (Rp)</div>
                <input type="number" id="amount" class="swal2-input !m-0 !w-full !rounded-2xl !text-sm !font-black" value="${nominal}">
            `,
            showCancelButton: true,
            confirmButtonText: 'Eksekusi',
            confirmButtonColor: '#1e3a8a',
            preConfirm: () => {
                const amount = Swal.getPopup().querySelector('#amount').value;
                if (!amount) Swal.showValidationMessage('Isi nominal denda (0 jika dimaafkan)');
                return { amount: amount };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/reportasset/${id}/penalty`;
                form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="amount" value="${result.value.amount}">`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endsection