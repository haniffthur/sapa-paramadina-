<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up() {
    Schema::create('peminjamans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->dateTime('start_time');
        $table->dateTime('end_time'); // Deadline pengembalian
        $table->dateTime('actual_return_time')->nullable(); // Waktu asli kembali
        $table->enum('status', ['pending', 'approved', 'rejected', 'active', 'completed', 'late'])->default('pending');
        
        $table->text('reason')->nullable();
        $table->text('admin_note')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
