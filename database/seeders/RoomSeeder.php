<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'name' => 'Lab Komputer (Lantai 2)',
                'qr_code_token' => 'FIR-KOMP-01',
                'prodi_id' => 1, // Milik Teknik Informatika
                'description' => 'Laboratorium utama untuk web development dan coding.'
            ],
            [
                'name' => 'Studio DKV (Lantai 3)',
                'qr_code_token' => 'FIR-DKV-02',
                'prodi_id' => 2, // Milik DKV
                'description' => 'Studio fotografi, lighting, dan produksi video.'
            ],
            [
                'name' => 'Lab Sistem Cerdas & IoT (Lantai 2)',
                'qr_code_token' => 'FIR-IOT-03',
                'prodi_id' => 1, // Milik Teknik Informatika
                'description' => 'Laboratorium untuk praktek hardware otomatisasi dan mikrokontroler.'
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}