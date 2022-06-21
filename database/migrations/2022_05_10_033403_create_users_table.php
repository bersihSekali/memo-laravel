<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();

            $table->unsignedBigInteger('satuan_kerja')->nullable();
            $table->unsignedBigInteger('departemen')->nullable();
            $table->unsignedBigInteger('cabang')->nullable();
            $table->unsignedBigInteger('bidang_cabang')->nullable();
            $table->unsignedBigInteger('level')->nullable();

            $table->foreign('satuan_kerja')->references('id')->on('satuan_kerjas');
            $table->foreign('departemen')->references('id')->on('departemens');
            $table->foreign('cabang')->references('id')->on('cabangs');
            $table->foreign('bidang_cabang')->references('id')->on('bidang_cabangs');
            $table->foreign('level')->references('id')->on('levels');

            $table->string('password');
            $table->string('email')->nullable();
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
        Schema::dropIfExists('users');
    }
}
