<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
   public function run()
{
    // Admin / Staff
    User::create([
        'name'      => 'Admin SAPA',
        'email'     => 'admin@paramadina.ac.id',
        'role'      => 'admin',
        'avatar'    => 'https://ui-avatars.com/api/?name=Admin+SAPA',
    ]);

    // Mahasiswa
    User::create([
        'name'      => 'mahasiswaa',
        'email'     => 'mahasiswaaa@students.paramadina.ac.id',
        'role'      => 'mahasiswa',
        'avatar'    => 'https://ui-avatars.com/api/?name=Mahasiswa+SAPA',
    ]);
}
}