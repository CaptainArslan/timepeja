<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('org_id');
            $table->foreign('org_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->integer('org_trail_days')->unsigned();
            $table->date('org_start_date');
            $table->date('org_end_date');
            $table->boolean('manager_wallet')->nullable();
            $table->boolean('driver_wallet')->nullable();
            $table->boolean('passenger_wallet')->nullable();
            $table->float('manager_payment')->nullable();
            $table->float('manager_amount')->nullable();
            $table->date('manager_trail_end_date')->nullable();
            $table->float('driver_payment')->nullable();
            $table->float('driver_amount')->nullable();
            $table->date('driver_trail_end_date')->nullable();
            $table->float('passenger_payment')->nullable();
            $table->float('passenger_amount')->nullable();
            $table->date('passenger_trail_end_date')->nullable();
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
        Schema::dropIfExists('financials');
    }
}
