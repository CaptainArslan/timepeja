<?php

use App\Models\Vehicle;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_type_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('organization_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('number');
            $table->string('no_of_seat')->nullable();
            $table->string('front_pic')->nullable();
            $table->string('back_pic')->nullable();
            $table->string('number_pic')->nullable();

            // Optional fields
            $table->string('reg_date')->nullable();
            $table->string('expiry_date')->nullable();
            $table->string('model_no')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('color')->nullable();
            $table->string('chassis_no')->nullable();
            $table->string('engine_no')->nullable();
            $table->string('car_accessories')->nullable();
            $table->boolean('status')->default(Vehicle::STATUS_ACTIVE);
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
        Schema::dropIfExists('vehicles');
    }
}
