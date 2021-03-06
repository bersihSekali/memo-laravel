<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSatuanKerjasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('satuan_kerjas', function (Blueprint $table) {
            $table->id();
            $table->string('satuan_kerja');
            $table->string('inisial')->nullable();

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
        Schema::dropIfExists('satuan_kerjas');
    }
}
