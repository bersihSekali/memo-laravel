<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratMasuksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_masuks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('otor_by');
            $table->integer('otor_status');
            $table->string('created_by');
            $table->string('nomor_surat')->unique();
            $table->string('perihal');
            $table->integer('satuan_kerja_asal');
            $table->integer('departemen_asal');
            $table->integer('satuan_kerja_tujuan');
            $table->integer('departemen_tujuan');
            $table->string('lampiran');
            $table->integer('checker');
            $table->datetime('tanggal_disposisi');
            $table->integer('satuan_kerja_tujuan_disposisi');
            $table->integer('departemen_tujuan_disposisi');
            $table->string('pesan_disposisi');
            $table->string('lampiran_disposisi');
            $table->datetime('tanggal_selesai');
            $table->integer('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surat_masuks');
    }
}
