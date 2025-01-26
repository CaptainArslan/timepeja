<?php

use App\Models\Schedule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('route_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('time');
            $table->enum('status', [
                Schedule::STATUS_DRAFT,
                Schedule::STATUS_PUBLISHED
            ])->default(Schedule::STATUS_DRAFT);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->tinyInteger('is_delayed')->default(Schedule::TRIP_NOTDELAYED);
            $table->enum('trip_status', [
                Schedule::TRIP_STATUS_UPCOMING,
                Schedule::TRIP_STATUS_INPROGRESS,
                Schedule::TRIP_STATUS_COMPLETED,
                Schedule::TRIP_STATUS_DELAYED,
            ])->default(Schedule::TRIP_STATUS_UPCOMING);

            $table->boolean('is_notified')->default(false);
            $table->text('delayed_reason')->nullable();
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
        Schema::dropIfExists('schedules');
    }
}
