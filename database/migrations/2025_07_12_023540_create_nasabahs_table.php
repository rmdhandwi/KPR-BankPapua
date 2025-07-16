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
        Schema::create('nasabah', function (Blueprint $table) {
            $table->id('id_nasabah');
            $table->unsignedBigInteger('id_rumah');
            $table->string('nama_lengkap');
            $table->string('no_ktp')->unique();
            $table->string('no_kk')->nullable();
            $table->string('no_npwp')->nullable();
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->string('pekerjaan');
            $table->string('nama_perusahan')->nullable();
            $table->text('alamat_perusahaan')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('penghasilan')->nullable();
            $table->string('no_tlp')->nullable();
            $table->string('kewarganegaraan');
            $table->string('status_perkawinan');
            $table->integer('jml_tanggungan')->default(0);
            $table->boolean('riwayat_pinjol')->default(false);
            $table->boolean('riwayat_kredit_macet')->default(false);
            $table->text('NPWP')->nullable();
            $table->text('KTP')->nullable();
            $table->text('surat_nikah')->nullable();
            $table->tetx('spt_tahunan')->nullable();
            $table->tetx('kartu_keluarga')->nullable();

            $table->foreign('id_rumah')->references('id_rumah')->on('rumah')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nasabah');
    }
};
