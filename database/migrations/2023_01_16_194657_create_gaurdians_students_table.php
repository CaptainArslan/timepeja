<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGaurdiansStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gaurdians_students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('g_id')->index('g_id')->nullable();
            $table->foreign('g_id')->references('id')->on('gaurdians');
            $table->unsignedBigInteger('s_id')->index('s_id')->nullable();
            $table->foreign('s_id')->references('id')->on('students');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gaurdians_students');
    }
}
