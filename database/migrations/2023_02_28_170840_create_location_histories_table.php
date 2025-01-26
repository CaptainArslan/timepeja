<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('location');
            $table->foreignId('passenger_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('type', ['vehicle','driver','passenger']);
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
        Schema::dropIfExists('location_histories');
    }
}
