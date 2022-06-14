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
            $table->boolean('all_flag');
            $table->foreign('satuan_kerja_id')->references('id')->on('satuan_kerjas');
            $table->foreign('memo_id')->references('id')->on('surat_keluars');
            $table->boolean('status_baca')->nullable();
            $table->date('tanggal_baca')->nullable();
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
