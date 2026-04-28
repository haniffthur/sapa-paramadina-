<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Categories extends Model {
    // WAJIB: Karena nama class jamak, kasih tahu tabelnya secara manual
    protected $table = 'categories';

    protected $fillable = ['name', 'slug'];

    protected static function boot()
    {
        parent::boot();
        
        // Debugging tip: pastiin Str di import di atas
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'category_id');
    }
}