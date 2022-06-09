<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Departemen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departemens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('satuan_kerja')->nullable();
            $table->foreign('satuan_kerja')->references('id')->on('satuan_kerjas');
            $table->string('departemen')->unique();

            $table->unsignedBigInteger('grup')->nullable();
            $table->foreign('grup')->references('id')->on('grups');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departemen');
    }
}
