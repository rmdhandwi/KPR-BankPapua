<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sub_kriteria', function (Blueprint $table) {
            $table->id('id_subkriteria');
            $table->string('nama_subkriteria');
            $table->decimal('bobot', 5, 2);
            $table->unsignedBigInteger('id_kriteria');

            $table->foreign('id_kriteria')->references('id_kriteria')->on('kriteria')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sub_kriteria');
    }
};
