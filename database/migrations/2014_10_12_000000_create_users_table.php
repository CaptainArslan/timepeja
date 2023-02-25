<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('r_id')->index('r_id')->nullable();
            $table->foreign('r_id')->references('id')->on('roles'); 
            $table->string('full_name');
            $table->string('user_name')->nullable();
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('password');
            $table->text('image')->nullable();
            $table->string('provider_id')->nullable();
            $table->string('provider')->nullable();
            $table->string('designation')->nullable();
            $table->string('verify_code')->unique();
            $table->integer('status');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
