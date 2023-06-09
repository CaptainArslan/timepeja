<?php




use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuardiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guardians', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('p_id')->index('p_id')->nullable();
            $table->foreign('p_id')->references('id')->on('passengers');
            $table->unsignedBigInteger('relation_id')->index('relation_id')->nullable();
            // $table->foreign('s_id')->references('id')->on('students');
            $table->string('guard_code')->nullable();
            $table->string('cnic')->nullable();
            $table->string('cnic_front_pic')->nullable();
            $table->string('cnic_back_pic')->nullable();
            $table->string('image')->nullable();
            $table->string('relation')->nullable();
            $table->boolean('status');
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
        Schema::dropIfExists('guardians');
    }
}
