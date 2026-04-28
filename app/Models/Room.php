<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model {
    protected $fillable = ['name', 'qr_code_token', 'prodi_id', 'description'];

    public function assets() {
        return $this->hasMany(Asset::class);
    }

    public function prodi() {
        return $this->belongsTo(Prodi::class);
    }
}