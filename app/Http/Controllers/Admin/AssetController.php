<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Room;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::with(['category', 'room'])->latest()->paginate(10);
        return view('admin.assets.index', compact('assets'));
    }

    public function create()
    {
        $rooms = Room::all();
        $categories = Categories::all();
        return view('admin.assets.create', compact('rooms', 'categories'));
    }

    public function store(Request $request)
    {
        // Tambahkan validasi untuk field audit
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'room_id' => 'required|exists:rooms,id',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:1',
            'tahun_beli' => 'nullable|integer',
            'kondisi' => 'nullable|string',
            'harga_beli' => 'nullable|numeric',
        ]);

        $data = $request->all();

        // Berikan nilai default 0 jika harga_beli kosong / null
        $data['harga_beli'] = $request->harga_beli ?? 0;

        // Penanganan upload image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('assets', 'public');
        }

        $data['status'] = 'available';
        Asset::create($data);

        return redirect()->route('admin.assets.index')->with('success', 'Aset Berhasil Terdaftar!');
    }

    public function show($id)
    {
        $asset = Asset::with(['room', 'category'])->findOrFail($id);
        return view('admin.assets.show', compact('asset'));
    }

    public function edit($id)
    {
        $asset = Asset::findOrFail($id);
        $rooms = Room::all();
        $categories = Categories::all();
        return view('admin.assets.edit', compact('asset', 'rooms', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);
        
        // Tambahkan validasi untuk update
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'room_id' => 'required|exists:rooms,id',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:1',
            'tahun_beli' => 'nullable|integer',
            'kondisi' => 'nullable|string',
            'harga_beli' => 'nullable|numeric',
        ]);

        $data = $request->all();

        // Berikan nilai default 0 jika harga_beli diubah jadi kosong
        $data['harga_beli'] = $request->harga_beli ?? 0;

        // Penanganan update image
        if ($request->hasFile('image')) {
            if ($asset->image) {
                Storage::disk('public')->delete($asset->image);
            }
            $data['image'] = $request->file('image')->store('assets', 'public');
        }

        $asset->update($data);
        return redirect()->route('admin.assets.index')->with('success', 'Data Aset Diperbarui!');
    }

    public function destroy($id)
    {
        $asset = Asset::findOrFail($id);
        if ($asset->image) {
            Storage::disk('public')->delete($asset->image);
        }
        $asset->delete();
        return redirect()->route('admin.assets.index')->with('success', 'Aset Berhasil Dihapus.');
    }
}