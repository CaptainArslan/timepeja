<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavouriteRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favourite_routes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('o_id')->index('o_id')->nullable();
            $table->unsignedBigInteger('p_id')->index('p_id')->nullable();
            $table->unsignedBigInteger('route_id')->index('route_id')->nullable();
            $table->boolean('status');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('o_id')->references('id')->on('organizations')->onUpdate('cascade');
            $table->foreign('p_id')->references('id')->on('passengers')->onUpdate('cascade');
            $table->foreign('route_id')->references('id')->on('routes')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favourite_routes');
    }
}
