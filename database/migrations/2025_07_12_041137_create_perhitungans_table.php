<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('perhitungan', function (Blueprint $table) {
            $table->id('id_perhitungan');
            $table->unsignedBigInteger('id_nasabah');
            $table->decimal('skor_akhir', 8, 2);
            $table->string('status_kelayakan');
            $table->date('tgl_hitung');

            $table->foreign('id_nasabah')->references('id_nasabah')->on('nasabah')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('perhitungan');
    }
};
