<?php




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
            $table->unsignedBigInteger('o_id')->index('o_id')->nullable();
            $table->unsignedBigInteger('u_id')->index('u_id')->nullable();
            $table->unsignedBigInteger('route_id')->index('route_id');
            $table->unsignedBigInteger('v_id')->index('v_id');
            $table->unsignedBigInteger('d_id')->index('d_id');
            $table->date('date');
            $table->time('time');
            $table->enum('status', ['0','1']);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('o_id')->references('id')->on('organizations')->onUpdate('cascade');
            // $table->foreign('u_id')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('route_id')->references('id')->on('routes');
            $table->foreign('v_id')->references('id')->on('vehicles');
            $table->foreign('d_id')->references('id')->on('drivers');
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
