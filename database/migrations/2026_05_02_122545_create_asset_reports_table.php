<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('asset_reports', function (Blueprint $table) {
            $table->id();
            // Aset mana yang rusak
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            
            // Petugas siapa yang ngelaporin (nyambung ke tabel users)
            $table->foreignId('petugas_id')->constrained('users')->cascadeOnDelete();
            
            // Penjelasan rusaknya kenapa
            $table->text('deskripsi_kerusakan');
            
            // Opsional: Petugas bisa upload foto bukti kerusakan
            $table->string('foto_kerusakan')->nullable();
            
            // Status laporannya ('menunggu' dicek admin, 'diproses' diperbaiki, 'selesai')
            $table->enum('status', ['menunggu', 'diproses', 'selesai'])->default('menunggu');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('asset_reports');
    }
};