<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersDriversStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_drivers_students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('u_id')->index('u_id')->nullable();
            $table->foreign('u_id')->references('id')->on('users');
            $table->unsignedBigInteger('d_id')->index('d_id')->nullable();
            $table->foreign('d_id')->references('id')->on('drivers');
            $table->unsignedBigInteger('s_id')->index('s_id')->nullable();
            $table->foreign('s_id')->references('id')->on('students');
            $table->integer('status');
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
        Schema::dropIfExists('users_drivers_students');
    }
}
