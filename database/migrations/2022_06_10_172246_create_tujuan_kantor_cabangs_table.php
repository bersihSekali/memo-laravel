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
            $table->unsignedBigInteger('cabang_id');

            $table->foreign('memo_id')->references('id')->on('surat_keluars');
            $table->foreign('cabang_id')->references('id')->on('cabangs');

            $table->integer('status_baca');
            $table->date('tanggal_baca');
            $table->string('pesan_disposisi');
            $table->date('tanggal_disposisi');
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
