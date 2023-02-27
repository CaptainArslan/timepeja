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

            $table->boolean('manager_wallet')->default(0);
            $table->boolean('manager_payment')->default(0);
            $table->float('manager_amount');
            $table->date('manager_trail_start_date');
            $table->date('manager_trail_end_date');

            $table->boolean('driver_wallet')->default(0);
            $table->boolean('driver_payment')->default(0);
            $table->float('driver_amount');
            $table->date('driver_trail_start_date');
            $table->date('driver_trail_end_date');

            $table->boolean('passenger_wallet')->default(0);
            $table->boolean('passenger_payment')->default(0);
            $table->float('passenger_amount');
            $table->date('passenger_trail_start_date');
            $table->date('passenger_trail_end_date');

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
