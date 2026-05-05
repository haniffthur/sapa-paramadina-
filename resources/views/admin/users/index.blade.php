@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Pengguna</h2>
        <p class="text-gray-500 text-sm">Monitor dan atur hak akses Civitas Paramadina</p>
    </div>
    
    <form action="{{ route('admin.users.index') }}" method="GET" class="flex gap-2">
        <input type="text" name="search" placeholder="Cari nama atau email..." 
               class="px-4 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none"
               value="{{ request('search') }}">
        <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded-xl text-sm hover:bg-blue-800 transition">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </form>
</div>

<!-- Menampilkan Alert Sukses -->
@if(session('success'))
    <div class="mb-4 bg-green-50 text-green-600 p-4 rounded-xl border border-green-100 text-sm font-bold flex items-center gap-2 shadow-sm">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="p-4 text-xs uppercase text-gray-500 font-bold">User</th>
                <th class="p-4 text-xs uppercase text-gray-500 font-bold text-center">Role Saat Ini</th>
                <th class="p-4 text-xs uppercase text-gray-500 font-bold text-right">Ubah Akses</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($users as $user)
            <tr class="hover:bg-gray-50 transition">
                <td class="p-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $user->avatar ?? asset('default-avatar.png') }}" class="w-10 h-10 rounded-full border shadow-sm object-cover">
                        <div>
                            <p class="font-bold text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500 italic">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="p-4 text-center">
                    <!-- UPDATE DI SINI: Ganti superadmin jadi petugas buat deteksi warna badge -->
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase 
                        {{ $user->role == 'petugas' ? 'bg-purple-100 text-purple-700' : 
                          ($user->role == 'admin' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                        {{ $user->role }}
                    </span>
                </td>
                <td class="p-4 text-right">
                    <form action="{{ route('admin.users.update-role', $user->id) }}" method="POST" class="flex justify-end gap-2">
                        @csrf
                        <select name="role" class="text-xs border rounded-lg px-2 py-1 outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                            <option value="mahasiswa" {{ $user->role == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <!-- UPDATE DI SINI: Value-nya udah diganti jadi petugas murni -->
                            <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        </select>
                        <button type="submit" class="bg-gray-800 text-white p-1.5 rounded-lg hover:bg-black transition" title="Simpan Perubahan">
                            <i class="fa-solid fa-floppy-disk"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="p-4 bg-gray-50">
        {{ $users->links() }}
    </div>
</div>
@endsection