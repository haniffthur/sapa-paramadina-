<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id', 
        'petugas_id', 
        'deskripsi_kerusakan', 
        'foto_kerusakan', 
        'status'
    ];

    // Relasi ke Aset
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    // Relasi ke Petugas (User)
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}