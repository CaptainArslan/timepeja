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
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name');
            $table->string('branch_name');
            $table->string('branch_code')->unique()->nullable();
            $table->unsignedBigInteger('org_type');
            $table->foreign('org_type')->references('id')->on('organization_types');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique();
            $table->text('address');
            $table->unsignedInteger('state');
            $table->unsignedInteger('city');
            $table->string('head_name');
            $table->string('head_email')->nullable();
            $table->string('head_phone')->nullable();
            $table->string('head_address')->nullable();
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
