<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'peminjaman_id', 
        'asset_id', 
        'petugas_id', 
        'deskripsi_kerusakan', 
        'foto_kerusakan', 
        'status',
        'nominal_denda',
    ];

    // Relasi balik ke Transaksi Peminjaman (Untuk tau siapa peminjamnya)
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    // Relasi ke Asset (Untuk tau barang apa yang rusak)
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    // Relasi ke User/Petugas (Untuk tau OB mana yang melapor)
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}