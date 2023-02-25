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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('o_id')->index('o_id')->nullable();
            $table->foreign('o_id')->references('id')->on('organizations')->onUpdate('cascade');
            $table->unsignedBigInteger('u_id')->index('u_id')->nullable();
            $table->foreign('u_id')->references('id')->on('users')->onUpdate('cascade');
            $table->unsignedBigInteger('v_type_id')->index('v_type_id')->nullable();
            $table->foreign('v_type_id')->references('id')->on('vehicle_types')->onUpdate('cascade');
            $table->string('number');
            $table->string('no_of_seat')->nullable();
            $table->string('front_pic')->nullable();
            $table->string('back_pic')->nullable();
            $table->string('number_pic')->nullable();

            // Optional fields
            $table->string('reg_date')->nullable();
            $table->string('expiry_date')->nullable();
            $table->string('model_no')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('color')->nullable();
            $table->string('chassis_no')->nullable();
            $table->string('engine_no')->nullable();
            $table->string('car_accessories')->nullable();
            $table->integer('status');
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
