<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Penalty; 

class Peminjaman extends Model {
    protected $table = 'peminjamans'; // Paksa nama tabel agar tidak jadi peminjamen
    protected $fillable = ['user_id', 'asset_id', 'qty_minjam', 'start_time', 'end_time', 'actual_return_time', 'status', 'reason', 'admin_note'];

    public function user() { return $this->belongsTo(User::class); }
    public function asset() { return $this->belongsTo(Asset::class); }
    
    public function penalty() {
        return $this->hasOne(Penalty::class);
    }
}