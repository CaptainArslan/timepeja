<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            // $table->unsignedBigInteger('s_id')->index('s_id')->nullable();
            // $table->foreign('s_id')->references('id')->on('states')->onUpdate('cascade');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('wikiDataId');
            $table->tinyInteger('flag');
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
        Schema::dropIfExists('cities');
    }
}
