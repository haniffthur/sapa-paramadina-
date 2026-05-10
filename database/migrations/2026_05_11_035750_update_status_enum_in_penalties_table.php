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
    Schema::table('penalties', function (Blueprint $table) {
        // Tambahkan 'pending' ke dalam daftar enum
        $table->enum('status', ['unpaid', 'pending', 'paid'])->default('unpaid')->change();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penalties', function (Blueprint $table) {
            //
        });
    }
};
