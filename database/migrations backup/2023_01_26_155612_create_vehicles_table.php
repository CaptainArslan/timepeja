<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->unsignedBigInteger('org_id');
            $table->foreign('org_id')->references('id')->on('organizations');
            $table->unsignedBigInteger('veh_id');
            $table->foreign('veh_id')->references('id')->on('vehicle_types');
            $table->string('veh_num');
            $table->string('veh_front_pic')->nullable();
            $table->string('veh_back_pic')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
