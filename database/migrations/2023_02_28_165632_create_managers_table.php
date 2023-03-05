<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('o_id')->index('o_id')->nullable();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique();
            $table->string('password');
            $table->string('otp')->unique();
            $table->string('token')->unique()->nullable();
            $table->longText('picture')->nullable();
            $table->longText('address')->nullable();
            $table->longText('about')->nullable();
            $table->integer('status');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('o_id')->references('id')->on('organizations')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('managers');
    }
}
