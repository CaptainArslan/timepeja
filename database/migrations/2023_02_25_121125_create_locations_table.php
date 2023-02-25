<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('o_id')->index('o_id')->nullable();
            $table->foreign('o_id')->references('id')->on('organizations')->onUpdate('cascade');
            $table->string('name');
            $table->unsignedBigInteger('d_id')->index('d_id')->nullable();
            $table->foreign('d_id')->references('id')->on('drivers')->onUpdate('cascade');
            $table->unsignedBigInteger('p_id')->index('p_id')->nullable();
            $table->foreign('p_id')->references('id')->on('passengers')->onUpdate('cascade');
            $table->unsignedBigInteger('v_id')->index('v_id')->nullable();
            $table->foreign('v_id')->references('id')->on('vehicles')->onUpdate('cascade');
            $table->enum('type', ['vehicle','driver','passenger']);
            $table->string('latitude');
            $table->string('longitude');
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
        Schema::dropIfExists('locations');
    }
}
