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

public function rooms()
{
    return $this->belongsToMany(Room::class, 'prodi_room');
}
public function users()
{
    return $this->hasMany(User::class, 'prodi_id');
}
}