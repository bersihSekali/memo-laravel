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
            $table->string('level')->nullable();

            $table->unsignedBigInteger('satuan_kerja')->nullable();
            $table->unsignedBigInteger('departemen')->nullable();

            $table->foreign('satuan_kerja')->references('id')->on('satuan_kerjas');
            $table->foreign('departemen')->references('id')->on('departemens');

            $table->string('password');
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
