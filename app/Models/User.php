<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use  HasFactory, Notifiable;

    /**
     * Field yang bisa diisi secara massal.
     */
    protected $fillable = [
        'google_id',
        'name',
        'email',
        'avatar',
        'password',
        'role',
        'nim',
        'prodi_id',
    ];

    /**
     * Field yang disembunyikan saat serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relasi ke Peminjaman (Satu user bisa punya banyak riwayat pinjam)
     */
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * Helper: Cek apakah user adalah Admin
     */
    public function isAdmin()
    {
        return in_array($this->role, ['admin', 'superadmin']);
    }
    public function prodi()
{
    return $this->belongsTo(Prodi::class, 'prodi_id');
}

public function reportsMade()
{
    // Sebagai Petugas/OB yang melaporkan
    return $this->hasMany(AssetReport::class, 'petugas_id');
}

public function penalties()
{
    // Sebagai Mahasiswa yang kena denda (Relasi ke tabel Penalty)
    return $this->hasMany(Penalty::class, 'user_id');
}
}