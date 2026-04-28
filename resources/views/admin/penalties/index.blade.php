@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Daftar Penalti & Denda</h2>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b">
                <tr class="text-[10px] font-black uppercase text-gray-400">
                    <th class="p-4">Mahasiswa</th>
                    <th class="p-4">Alasan Denda</th>
                    <th class="p-4">Jumlah</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($penalties as $p)
                <tr class="text-sm">
                    <td class="p-4">
                        <span class="font-bold text-gray-800">{{ $p->user->name }}</span>
                    </td>
                    <td class="p-4 text-gray-500">{{ $p->description }}</td>
                    <td class="p-4 font-bold text-red-600">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                    <td class="p-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $p->status == 'paid' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                            {{ $p->status }}
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        @if($p->status == 'unpaid')
                        <form action="{{ route('admin.penalties.paid', $p->id) }}" method="POST">
                            @csrf
                            <button class="bg-green-600 text-white px-4 py-2 rounded-xl text-xs font-bold">Lunaskan</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection