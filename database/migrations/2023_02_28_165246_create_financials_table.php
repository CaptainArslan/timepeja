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
            $table->bigIncrements('id');

            $table->unsignedBigInteger('u_id')->index('u_id');
            // $table->foreign('u_id')->references('id')->on('users');
            
            $table->unsignedBigInteger('o_id')->index('o_id');
            
            $table->boolean('org_wallet')->default(0);
            $table->boolean('org_payment')->default(0);
            $table->float('org_amount');
            $table->date('org_trail_start_date');
            $table->date('org_trail_end_date');
            
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
            $table->foreign('o_id')->references('id')->on('organizations')->onDelete('cascade');
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
