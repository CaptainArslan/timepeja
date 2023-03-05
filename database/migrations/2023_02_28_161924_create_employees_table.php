<?php




use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('p_id')->index('p_id')->nullable();
            $table->foreign('p_id')->references('id')->on('passengers');
            $table->string('card_id')->nullable();
            $table->string('card_id_pic')->nullable();
            $table->string('discipline')->nullable();
            $table->string('designaiton')->nullable();
            $table->string('degree_duration')->nullable();
            $table->integer('status');
            $table->rememberToken();
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
        Schema::dropIfExists('employees');
    }
}
