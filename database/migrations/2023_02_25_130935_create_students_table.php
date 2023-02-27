<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('p_id')->index('p_id')->nullable();
            $table->foreign('p_id')->references('id')->on('passengers');
            $table->string('roll_no');
            $table->string('class');
            $table->string('section');
            $table->string('card_id')->nullable();
            $table->string('card_id_pic')->nullable();
            $table->string('qualification')->nullable();
            $table->string('discipline')->nullable();
            $table->string('degree_duration')->nullable();
            $table->string('unique_id');
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
        Schema::dropIfExists('students');
    }
}
