<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTujuanSatuanKerjasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tujuan_satuan_kerjas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('memo_id');
            $table->unsignedBigInteger('satuan_kerja_id');
            $table->foreign('satuan_kerja_id')->references('id')->on('satuan_kerjas');
            $table->foreign('memo_id')->references('id')->on('surat_keluars');
            $table->boolean('status_baca');
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
        Schema::dropIfExists('tujuan_satuan_kerjas');
    }
}
