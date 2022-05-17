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
            $table->string('otor_status')->default(1);
            $table->string('created_by');
            $table->string('nomor_surat')->unique()->nullable();
            $table->string('perihal');

            $table->unsignedBigInteger('satuan_kerja_asal');
            $table->unsignedBigInteger('departemen_asal');
            $table->unsignedBigInteger('satuan_kerja_tujuan');
            $table->unsignedBigInteger('departemen_tujuan');

            $table->foreign('satuan_kerja_asal')->references('id')->on('satuan_kerjas');
            $table->foreign('departemen_asal')->references('id')->on('departemens');
            $table->foreign('satuan_kerja_tujuan')->references('id')->on('satuan_kerjas');
            $table->foreign('departemen_tujuan')->references('id')->on('departemens');

            $table->string('lampiran')->nullable();
            $table->string('checker')->nullable();
            $table->datetime('tanggal_disposisi')->nullable();

            $table->unsignedBigInteger('satuan_kerja_tujuan_disposisi')->nullable();
            $table->unsignedBigInteger('departemen_tujuan_disposisi')->nullable();

            $table->foreign('satuan_kerja_tujuan_disposisi')->references('id')->on('satuan_kerjas');
            $table->foreign('departemen_tujuan_disposisi')->references('id')->on('departemens');

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
