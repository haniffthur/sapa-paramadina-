<?php

namespace App\Events;

use App\Models\Peminjaman;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class PeminjamanCompleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $peminjaman;
    public $message;

    public function __construct(Peminjaman $peminjaman)
    {
        $this->peminjaman = $peminjaman;

        $this->message = "Barang telah dikembalikan. Terima kasih!";
    }

    public function broadcastOn()
    {
       return [
        new Channel('peminjaman-channel'), // Buat Petugas (Public/Umum)
        new Channel('mahasiswa-channel.' . $this->peminjaman->user_id), // Buat si Mahasiswa (Private)
        new Channel('admin-notification') // Buat Admin (Public/Umum)
    ];
    }

    public function broadcastAs()
    {
        return 'loan-completed';
    }
}