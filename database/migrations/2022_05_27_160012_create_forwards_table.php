<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forwards', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('memo_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('memo_id')->references('id')->on('surat_keluars');
            $table->boolean('status_baca')->nullable();
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
        Schema::dropIfExists('forwards');
    }
}
