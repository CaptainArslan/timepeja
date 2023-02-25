<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('o_id')->index('o_id')->nullable();
            $table->foreign('o_id')->references('id')->on('organizations')->onUpdate('cascade');
            $table->unsignedBigInteger('u_id')->index('u_id')->nullable();
            $table->foreign('u_id')->references('id')->on('users')->onUpdate('cascade');
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->string('phone');
            $table->string('cnic')->unique();
            $table->string('cnic_front_pic')->nullable();
            $table->string('cnic_back_pic')->nullable();
            $table->date('cnic_expiry_date')->nullable();
            $table->string('licence_no')->unique();
            $table->string('licence_no_front_pic')->nullable();
            $table->string('licence_no_back_pic')->nullable();
            $table->date('licence_expiry_date')->nullable();
            $table->string('otp')->unique();
            $table->string('token')->unique()->nullable();
            $table->integer('status');
            $table->integer('online_status');
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
        Schema::dropIfExists('drivers');
    }
}
