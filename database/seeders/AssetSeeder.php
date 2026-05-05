<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asset;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $assets = [
            // Aset Lab Komputer (Category: Komputer, Room: Lab Komp)
            [
                'name' => 'Apple iMac 24-inch M1',
                'room_id' => 1,
                'category_id' => 1,
                'quantity' => 15,
                'status' => 'available',
                'tahun_beli' => 2022,
                'kondisi' => 'baik',
                'harga_beli' => 24500000.00,
                'description' => 'PC All-in-one untuk kebutuhan lab komputasi dasar.',
            ],
            
            // Aset Studio DKV (Category: Studio, Room: Studio DKV)
            [
                'name' => 'Kamera Sony A7 III',
                'room_id' => 2,
                'category_id' => 2,
                'quantity' => 3,
                'status' => 'available',
                'tahun_beli' => 2021,
                'kondisi' => 'baik',
                'harga_beli' => 28000000.00,
                'description' => 'Kamera mirrorless full-frame dengan lensa kit.',
            ],

            // Aset Lab IoT (Category: Hardware & IoT, Room: Lab Sistem Cerdas)
            [
                'name' => 'ESP32 Development Board',
                'room_id' => 3,
                'category_id' => 3,
                'quantity' => 30,
                'status' => 'available',
                'tahun_beli' => 2023,
                'kondisi' => 'baru',
                'harga_beli' => 85000.00,
                'description' => 'Modul WiFi & Bluetooth untuk project IoT.',
            ],
            [
                'name' => 'Arduino Mega 2560',
                'room_id' => 3,
                'category_id' => 3,
                'quantity' => 10,
                'status' => 'available',
                'tahun_beli' => 2020,
                'kondisi' => 'rusak_ringan',
                'harga_beli' => 250000.00,
                'description' => 'Mikrokontroler dengan pin I/O yang banyak.',
            ],
        ];

        foreach ($assets as $asset) {
            Asset::create($asset);
        }
    }
}