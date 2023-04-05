<?php

use App\Models\Driver;
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
            $table->unsignedBigInteger('u_id')->index('u_id')->nullable();
            // $table->foreign('u_id')->references('id')->on('users')->onUpdate('cascade');
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('phone');
            $table->string('cnic')->unique();
            $table->longText('profile_picture')->nullable();
            $table->string('cnic_front_pic')->nullable();
            $table->string('cnic_back_pic')->nullable();
            $table->date('cnic_expiry_date')->nullable();
            $table->string('license_no')->unique();
            $table->string('license_no_front_pic')->nullable();
            $table->string('license_no_back_pic')->nullable();
            $table->date('license_expiry_date')->nullable();
            $table->string('otp')->unique();
            // $table->string('token')->unique()->nullable();
            $table->integer('status');
            $table->integer('online_status')->default(Driver::STATUS_OFFLINE);
            $table->text('address')->nullable();
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
        Schema::dropIfExists('drivers');
    }
}
