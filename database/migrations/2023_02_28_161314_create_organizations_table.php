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
            $table->foreignId('organization_type_id')->index('organization_type_id')->nullable();
            $table->string('name');
            $table->string('branch_name')->nullable();
            $table->string('branch_code')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->string('code');
            $table->foreignId('state_id')->nullable()->constrained('states');
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->json('address');
            $table->string('head_name');
            $table->string('head_email');
            $table->string('head_phone');
            $table->json('head_address')->nullable();
            $table->boolean('status');
            $table->string('deactivate_code')->nullable();
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
