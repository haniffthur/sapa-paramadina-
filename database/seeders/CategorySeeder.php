<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Komputer & Laptop'],
            ['name' => 'Studio & Fotografi'],
            ['name' => 'Hardware & IoT'],
            ['name' => 'Ruangan & Fasilitas'],
        ];

        foreach ($categories as $cat) {
            Categories::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name'])
            ]);
        }
    }
}