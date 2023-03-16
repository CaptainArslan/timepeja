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
            $table->string('branch_name')->nullable();
            $table->string('branch_code')->nullable();
            $table->unsignedBigInteger('o_type_id')->index('o_type_id')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->unsignedBigInteger('s_id')->nullable();
            $table->unsignedBigInteger('c_id')->nullable();
            $table->string('address');
            $table->string('head_name');
            $table->string('head_email');
            $table->string('head_phone');
            $table->string('head_address')->nullable();
            $table->integer('status');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('o_type_id')->references('id')->on('organization_types');
            $table->foreign('s_id')->references('id')->on('states')->onUpdate('cascade');
            $table->foreign('c_id')->references('id')->on('cities')->onUpdate('cascade');
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
