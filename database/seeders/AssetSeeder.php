<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Categories; // Model yang lo pake
use App\Models\Prodi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Data Prodi
        $prodiTI = Prodi::updateOrCreate(['nama_prodi' => 'Teknik Informatika']);
        $prodiDKV = Prodi::updateOrCreate(['nama_prodi' => 'Desain Komunikasi Visual']);

        // 2. Buat Kategori (Sertakan slug manual biar gak error SQL)
        $catRuangan = Categories::updateOrCreate(
            ['name' => 'Ruangan & Lab'],
            ['slug' => Str::slug('Ruangan & Lab')]
        );

        $catElektronik = Categories::updateOrCreate(
            ['name' => 'Elektronik & Gadget'],
            ['slug' => Str::slug('Elektronik & Gadget')]
        );

        // 3. Tambah Data Aset
        // Lab Studio Game (TI)
        Asset::create([
            'prodi_id' => $prodiTI->id,
            'category_id' => $catRuangan->id,
            'name' => 'Lab Studio Game',
            'description' => 'Laboratorium komputer spesifikasi tinggi untuk pengembangan game.',
            'status' => 'available',
            'qr_code_token' => 'LAB-GAME-' . strtoupper(Str::random(6)),
            'quantity' => 1,
        ]);

        // Lab DKV (DKV)
        Asset::create([
            'prodi_id' => $prodiDKV->id,
            'category_id' => $catRuangan->id,
            'name' => 'Lab DKV',
            'description' => 'Ruangan desain dengan pen tablet dan iMac.',
            'status' => 'available',
            'qr_code_token' => 'LAB-DKV-' . strtoupper(Str::random(6)),
            'quantity' => 1,
        ]);

        // Oculus Quest (TI)
        Asset::create([
            'prodi_id' => $prodiTI->id,
            'category_id' => $catElektronik->id,
            'name' => 'Oculus Quest 2',
            'description' => 'Virtual Reality Headset untuk testing game VR.',
            'status' => 'available',
            'qr_code_token' => 'VR-OQ2-' . strtoupper(Str::random(6)),
            'quantity' => 5,
        ]);

        // Tambahan: Kamera (DKV)
        Asset::create([
            'prodi_id' => $prodiDKV->id,
            'category_id' => $catElektronik->id,
            'name' => 'Sony A7 IV',
            'description' => 'Kamera Mirrorless untuk kebutuhan produksi video prodi DKV.',
            'status' => 'available',
            'qr_code_token' => 'CAM-SONY-' . strtoupper(Str::random(6)),
            'quantity' => 2,
        ]);
    }
}