<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahin NIM (nullable karena pas awal login Google masih kosong)
            $table->string('nim')->nullable()->after('email');
            
            // Relasi ke tabel prodis
            $table->foreignId('prodi_id')->nullable()->after('nim')->constrained('prodis')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['prodi_id']);
            $table->dropColumn(['nim', 'prodi_id']);
        });
    }
};