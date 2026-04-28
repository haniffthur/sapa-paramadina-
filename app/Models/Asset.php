<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'room_id',
        'category_id',
        'quantity',
        'status',
        'tahun_beli',
        'kondisi',
        'harga_beli',
        'description',
        'image'
    ];

    /**
     * Relasi: Satu Aset berada di satu Ruangan
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Relasi: Satu Aset memiliki satu Kategori
     */
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    /**
     * Relasi: Satu Aset bisa dipinjam berkali-kali
     */
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }
}