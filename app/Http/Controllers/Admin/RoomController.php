<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('prodi')->latest()->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $prodis = Prodi::all();
        return view('admin.rooms.create', compact('prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prodi_id' => 'required|exists:prodis,id',
        ]);

        // Generate Token QR Pintu (Cukup sekali per ruangan)
        $token = 'ROOM-' . strtoupper(Str::random(6));

        Room::create([
            'name' => $request->name,
            'prodi_id' => $request->prodi_id,
            'qr_code_token' => $token,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.rooms.index')->with('success', 'Ruangan berhasil dibuat!');
    }

    public function show($id)
    {
        $room = Room::with(['assets.category', 'prodi'])->findOrFail($id);
        // URL yang akan ditanam di QR Pintu
        $qrUrl = route('scan.room', $room->qr_code_token);

        return view('admin.rooms.show', compact('room', 'qrUrl'));
    }

    // Tambahkan method edit, update, destroy sesuai kebutuhan CRUD standar
}