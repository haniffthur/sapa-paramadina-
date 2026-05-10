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
    Schema::table('asset_reports', function (Blueprint $table) {
        // Nambahin kolom nominal denda setelah kolom foto
        $table->integer('nominal_denda')->nullable()->after('foto_kerusakan');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_reports', function (Blueprint $table) {
            //
        });
    }
};
