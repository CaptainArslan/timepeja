<?php

use App\Models\Trip;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('u_id')->index('u_id')->nullable();
            // $table->unsignedBigInteger('o_id')->index('o_id')->nullable();
            // $table->foreign('u_id')->references('id')->on('users')->onUpdate('cascade');
            $table->unsignedBigInteger('sch_id')->index('sch_id')->nullable();
            $table->time('actual_start_time ')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->boolean('delayed')->default(0);
            $table->text('delay_reason')->nullable();
            $table->enum('status', [
                Trip::STATUS_PENDING,
                Trip::STATUS_INPROGRESS,
                Trip::STATUS_COMPLETED,
                Trip::STATUS_DELAY
            ]);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('sch_id')->references('id')->on('schedules')->onUpdate('cascade');
            // $table->foreign('o_id')->references('id')->on('organizations')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trips');
    }
}
