<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavoriteRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorite_routes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('o_id')->index('o_id')->nullable();
            $table->foreign('o_id')->references('id')->on('organizations')->onUpdate('cascade');
            $table->unsignedBigInteger('p_id')->index('p_id')->nullable();
            $table->foreign('p_id')->references('id')->on('passengers')->onUpdate('cascade');
            $table->unsignedBigInteger('route_id')->index('route_id')->nullable();
            $table->foreign('route_id')->references('id')->on('routes')->onUpdate('cascade');
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
        Schema::dropIfExists('favorite_routes');
    }
}
