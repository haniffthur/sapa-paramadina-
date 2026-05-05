<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prodi;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        $prodis = [
            ['nama_prodi' => 'Teknik Informatika'],
            ['nama_prodi' => 'Desain Komunikasi Visual'],
            ['nama_prodi' => 'Desain Produk'],
        ];

        foreach ($prodis as $prodi) {
            Prodi::create($prodi);
        }
    }
}