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
            $table->unsignedBigInteger('u_id')->index('u_id')->nullable();
            $table->unsignedBigInteger('o_id')->index('o_id')->nullable();
            $table->string('name');
            $table->unsignedBigInteger('p_id')->index('p_id')->nullable();
            $table->unsignedBigInteger('v_id')->index('v_id')->nullable();
            $table->unsignedBigInteger('d_id')->index('d_id')->nullable();
            $table->enum('type', ['vehicle','driver','passenger']);
            $table->string('latitude');
            $table->string('longitude');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('o_id')->references('id')->on('organizations')->onUpdate('cascade');
            $table->foreign('p_id')->references('id')->on('passengers')->onUpdate('cascade');
            $table->foreign('v_id')->references('id')->on('vehicles')->onUpdate('cascade');
            $table->foreign('d_id')->references('id')->on('drivers')->onUpdate('cascade');
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
