<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Tambahin 'petugas' ke daftar ENUM sementara
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('mahasiswa', 'admin', 'superadmin', 'petugas') DEFAULT 'mahasiswa'");

        // 2. Ubah semua user yang tadinya 'superadmin' menjadi 'petugas'
        DB::table('users')->where('role', 'superadmin')->update(['role' => 'petugas']);

        // 3. Hapus 'superadmin' dari daftar ENUM secara permanen
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('mahasiswa', 'admin', 'petugas') DEFAULT 'mahasiswa'");
    }

    public function down()
    {
        // Ini buat jaga-jaga kalau lo mau ngebatalin (rollback) migration-nya
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('mahasiswa', 'admin', 'superadmin', 'petugas') DEFAULT 'mahasiswa'");
        DB::table('users')->where('role', 'petugas')->update(['role' => 'superadmin']);
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('mahasiswa', 'admin', 'superadmin') DEFAULT 'mahasiswa'");
    }
};