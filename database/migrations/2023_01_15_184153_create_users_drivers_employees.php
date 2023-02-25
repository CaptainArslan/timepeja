<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersDriversEmployees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_drivers_employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('u_id')->index('u_id')->nullable();
            $table->foreign('u_id')->references('id')->on('users');
            $table->unsignedBigInteger('d_id')->index('d_id')->nullable();
            $table->foreign('d_id')->references('id')->on('drivers');
            $table->unsignedBigInteger('e_id')->index('e_id')->nullable();
            $table->foreign('e_id')->references('id')->on('employees');
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
        Schema::dropIfExists('users_drivers_employees');
    }
}
