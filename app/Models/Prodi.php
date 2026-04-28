<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model {
    protected $fillable = ['nama_prodi'];

public function assets()
{
    // Cari aset LEWAT ruangan (Has Many Through)
    return $this->hasManyThrough(Asset::class, Room::class);
}
}