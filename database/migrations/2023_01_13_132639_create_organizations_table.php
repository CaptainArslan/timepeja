<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('u_id')->index('u_id')->nullable();
            $table->foreign('u_id')->references('id')->on('users');
            $table->string('branch_name');
            $table->string('branch_code');
            $table->unsignedBigInteger('o_type')->index('o_type')->nullable();
            $table->foreign('o_type')->references('id')->on('organization_types');
            $table->string('email');
            $table->string('phone');
            $table->unsignedBigInteger('s_id')->index('s_id')->nullable();
            $table->foreign('s_id')->references('id')->on('states');
            $table->unsignedBigInteger('c_id')->index('c_id')->nullable();
            $table->foreign('c_id')->references('id')->on('cities');
            $table->string('address');
            $table->string('head_name')->nullable();
            $table->string('head_email')->nullable();
            $table->string('head_phone')->nullable();
            $table->string('head_address')->nullable();
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
        Schema::dropIfExists('organizations');
    }
}
