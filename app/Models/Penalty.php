<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penalty extends Model {
    protected $fillable = ['peminjaman_id', 'user_id', 'amount', 'type', 'description', 'status','payment_proof'];

    public function peminjaman() {
        return $this->belongsTo(Peminjaman::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}