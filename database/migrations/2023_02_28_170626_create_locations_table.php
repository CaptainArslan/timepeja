<?php




use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('user_id')->nullable();
            $table->unsignedBigInteger('organization_id')->index('organization_id')->nullable();
            $table->string('name');
            $table->unsignedBigInteger('passenger_id')->index('passenger_id')->nullable();
            $table->unsignedBigInteger('vehicle_id')->index('vehicle_id')->nullable();
            $table->unsignedBigInteger('driver_id')->index('driver_id')->nullable();
            $table->enum('type', ['vehicle','driver','passenger']);
            $table->string('latitude');
            $table->string('longitude');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('organization_id')->references('id')->on('organizations')->onUpdate('cascade');
            $table->foreign('passenger_id')->references('id')->on('passengers')->onUpdate('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onUpdate('cascade');
            $table->foreign('driver_id')->references('id')->on('drivers')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
