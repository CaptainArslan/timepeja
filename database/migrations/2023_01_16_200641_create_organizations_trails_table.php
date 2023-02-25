<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTrailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations_trails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('from_date');
            $table->string('to_date');
            $table->double('estimated_days');
            $table->unsignedBigInteger('o_id')->index('o_id')->nullable();
            $table->foreign('o_id')->references('id')->on('organizations');
            $table->unsignedBigInteger('w_id')->index('w_id')->nullable();
            $table->foreign('w_id')->references('id')->on('wallets');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organizations_trails');
    }
}
