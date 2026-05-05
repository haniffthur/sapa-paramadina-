<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Penalty; 

class Peminjaman extends Model {
    protected $table = 'peminjamans'; // Paksa nama tabel agar tidak jadi peminjamen
    protected $fillable = ['user_id', 'start_time', 'end_time', 'actual_return_time', 'status', 'reason', 'admin_note'];

    public function details()
{
    return $this->hasMany(PeminjamanDetail::class);
}

// Relasi ke User tetap ada
public function user()
{
    return $this->belongsTo(User::class);
}
    public function asset()
{
    return $this->belongsTo(Asset::class)->withDefault();
}
    
    public function penalty() {
        return $this->hasOne(Penalty::class);
    }

    
}