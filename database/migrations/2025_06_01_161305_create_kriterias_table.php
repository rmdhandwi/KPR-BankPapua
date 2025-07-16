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
        Schema::create('kriteria', function (Blueprint $table) {
            $table->id('id_kriteria');
            $table->string('nama_kriteria');
            $table->decimal('bobot', 5, 2); // nilai bobot antara 0.00 - 99.99
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kriteria');
    }
};
