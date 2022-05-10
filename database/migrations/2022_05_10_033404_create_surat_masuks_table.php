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
            $table->string('otor_by')->nullable();
            $table->string('otor_status');
            $table->string('created_by');
            $table->string('nomor_surat')->unique()->nullable();
            $table->string('perihal');
            $table->string('satuan_kerja_asal');
            $table->string('departemen_asal');
            $table->string('satuan_kerja_tujuan');
            $table->string('departemen_tujuan');
            $table->string('lampiran')->nullable();
            $table->string('checker')->nullable();
            $table->datetime('tanggal_disposisi')->nullable();
            $table->string('satuan_kerja_tujuan_disposisi')->nullable();
            $table->string('departemen_tujuan_disposisi')->nullable();
            $table->string('pesan_disposisi')->nullable();
            $table->string('lampiran_disposisi')->nullable();
            $table->datetime('tanggal_selesai')->nullable();
            $table->string('status')->nullable();
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
