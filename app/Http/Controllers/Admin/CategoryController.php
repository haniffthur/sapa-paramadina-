<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Categories::withCount('assets')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:categories,name']);
        Categories::create(['name' => $request->name]);
        return redirect()->back()->with('success', 'Kategori berhasil ditambah!');
    }

    public function destroy($id)
    {
        $category = Categories::findOrFail($id);
        // Cek dulu apakah ada aset yang pakai kategori ini
        if ($category->assets()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal hapus! Masih ada aset di kategori ini.');
        }
        $category->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}