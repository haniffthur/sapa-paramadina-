<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanDetail extends Model
{
    protected $fillable = ['peminjaman_id', 'asset_id', 'quantity'];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }
}