<?php

use App\Models\Requests;
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
            $table->id();
            // $table->unsignedBigInteger('p_id')->index('p_id')->nullable();
            // $table->foreign('p_id')->references('id')->on('passengers');
            // $table->foreignId('request_id')->constrained('requests')->onDelete('cascade');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('image');
            $table->string('house_no');
            $table->string('street_no')->nullable();
            $table->string('town')->nullable();
            $table->string('additional_detail')->nullable();
            $table->string('city_id')->nullable();
            $table->string('pickup_address')->nullable();
            $table->string('pickup_city_id')->nullable();
            $table->string('lattitude')->nullable();
            $table->string('longitude')->nullable();
            $table->enum('type', [
                Requests::STUDENT_SCHOOL,
                Requests::STUDENT_COLLEGE,
                Requests::STUDENT_UNIVERSITY,
            ]);
            $table->boolean('status')->default(true);
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
