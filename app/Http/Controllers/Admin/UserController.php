<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Tambahin with('prodi') biar query ke database lebih efisien
        $users = User::with('prodi')->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update(['role' => $request->role]);
        return redirect()->back()->with('success', 'Role user berhasil diubah.');
    }
}