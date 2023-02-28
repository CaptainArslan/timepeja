<?php



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
            $table->unsignedBigInteger('o_id')->index('o_id')->nullable();
            $table->unsignedBigInteger('u_id')->index('u_id')->nullable();
            // $table->foreign('u_id')->references('id')->on('users')->onUpdate('cascade');
            $table->unsignedBigInteger('sch_id')->index('sch_id')->nullable();
            $table->string('delay_reason')->nullable();
            $table->time('delay_time')->nullable();
            $table->enum('status', ['pending','in_progress','completed','delay']);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('o_id')->references('id')->on('organizations')->onUpdate('cascade');
            $table->foreign('sch_id')->references('id')->on('schedules')->onUpdate('cascade');
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
