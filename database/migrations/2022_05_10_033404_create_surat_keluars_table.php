<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratKeluarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_keluars', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('otor2_by')->nullable();
            $table->unsignedBigInteger('otor1_by')->nullable();
            $table->unsignedBigInteger('otor2_by_pengganti')->nullable();
            $table->unsignedBigInteger('otor1_by_pengganti')->nullable();
            $table->unsignedBigInteger('created_by');

            $table->foreign('otor2_by')->references('id')->on('users');
            $table->foreign('otor1_by')->references('id')->on('users');
            $table->foreign('otor2_by_pengganti')->references('id')->on('users');
            $table->foreign('otor1_by_pengganti')->references('id')->on('users');
            $table->foreign('created_by')->references('id')->on('users');

            $table->datetime('tanggal_otor2')->nullable();
            $table->datetime('tanggal_otor1')->nullable();
            $table->string('nomor_surat')->unique()->nullable();
            $table->string('perihal');
            $table->unsignedBigInteger('no_urut')->nullable();

            $table->unsignedBigInteger('satuan_kerja_asal');
            $table->unsignedBigInteger('departemen_asal')->nullable();

            $table->foreign('satuan_kerja_asal')->references('id')->on('satuan_kerjas');
            $table->foreign('departemen_asal')->references('id')->on('departemens');

            $table->string('lampiran')->nullable();
            $table->string('pesan_tolak')->nullable();
            $table->unsignedBigInteger('internal');

            $table->unsignedBigInteger('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surat_keluars');
    }
}
