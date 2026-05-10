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
    Schema::create('settings', function (Blueprint $table) {
        $table->id();
        $table->string('key')->unique(); // Misal: 'penalty_per_hour'
        $table->string('value');
        $table->timestamps();
    });

    // Isi denda default lewat migration
    DB::table('settings')->insert([
        'key' => 'penalty_per_hour',
        'value' => '5000', // Default denda 5rb per jam
    ]);
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
