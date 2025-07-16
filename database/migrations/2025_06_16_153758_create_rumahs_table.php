<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rumah', function (Blueprint $table) {
            $table->id('id_rumah');
            $table->string('nama');
            $table->string('tipe');
            $table->decimal('luas_bangunan', 8, 2); 
            $table->decimal('luas_tanah', 8, 2);
            $table->decimal('harga', 15, 2); // 
            $table->text('karakteristik')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rumah');
    }
};
