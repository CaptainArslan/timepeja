<?php

use App\Models\Route;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('o_id')->index('o_id')->nullable();
            $table->unsignedBigInteger('u_id')->index('u_id')->nullable();
            // $table->foreign('u_id')->references('id')->on('users')->onUpdate('cascade');
            $table->string('name');
            $table->string('number');
            $table->string('from');
            $table->string('from_latitude')->nullable();
            $table->string('from_longitude')->nullable();
            $table->string('to');
            $table->string('to_latitude')->nullable();
            $table->string('to_longitude')->nullable();
            $table->boolean('status')->default(Route::STATUS_ACTIVE);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('o_id')->references('id')->on('organizations')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes');
    }
}
