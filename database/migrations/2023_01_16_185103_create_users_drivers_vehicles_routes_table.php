<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersDriversVehiclesRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_drivers_vehicles_routes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('u_id')->index('u_id')->nullable();
            $table->foreign('u_id')->references('id')->on('users');
            $table->unsignedBigInteger('d_id')->index('d_id')->nullable();
            $table->foreign('d_id')->references('id')->on('drivers');
            $table->unsignedBigInteger('v_id')->index('v_id')->nullable();
            $table->foreign('v_id')->references('id')->on('vehicles');
            $table->unsignedBigInteger('route_id')->index('route_id')->nullable();
            $table->foreign('route_id')->references('id')->on('routes');
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
        Schema::dropIfExists('users_drivers_vehicles_routes');
    }
}
