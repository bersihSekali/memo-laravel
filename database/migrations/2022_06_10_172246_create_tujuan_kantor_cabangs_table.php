<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTujuanKantorCabangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tujuan_kantor_cabangs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('memo_id');
            $table->unsignedBigInteger('cabang_id')->nullable();
            $table->unsignedBigInteger('bidang_id')->nullable();
            $table->boolean('all_flag');

            $table->foreign('memo_id')->references('id')->on('surat_keluars');
            $table->foreign('cabang_id')->references('id')->on('cabangs');
            $table->foreign('bidang_id')->references('id')->on('bidang_cabangs');

            $table->integer('status_baca')->nullable();
            $table->date('tanggal_baca')->nullable();
            $table->string('pesan_disposisi')->nullable();
            $table->date('tanggal_disposisi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tujuan_kantor_cabangs');
    }
}
