<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            
            // Relasi ke Ruangan & Kategori
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            
            // Data Stok & Operasional
            $table->integer('quantity')->default(1);
            $table->enum('status', ['available', 'maintenance', 'unavailable'])->default('available');
            
            // Data Audit & Pencatatan (BARU)
            $table->year('tahun_beli')->nullable();
            $table->enum('kondisi', ['baru', 'baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->decimal('harga_beli', 15, 2)->default(0);
            
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
