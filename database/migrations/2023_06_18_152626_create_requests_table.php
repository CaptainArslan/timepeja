<?php

use App\Models\Requests;
use Faker\Core\Uuid;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passenger_id')->constrained('passengers')->onDelete('cascade');
            $table->foreignId('o_id')->constrained('organizations')->onDelete('cascade');
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('house_no');
            $table->string('street_no');
            $table->string('town');
            $table->string('additional_detail')->nullable();
            $table->string('city_id')->nullable();
            $table->string('pickup_address')->nullable();
            $table->string('pickup_city_id')->nullable();
            $table->string('lattitude')->nullable();
            $table->string('longitude')->nullable();
            $table->enum('request_types', [
                Requests::REQUEST_STATUS_STUDENT,
                Requests::REQUEST_STATUS_EMPLOYEE,
                Requests::REQUEST_STATUS_GUARDIAN,
            ]);
            $table->enum('student_type', [
                Requests::STUDENT_SCHOOL,
                Requests::STUDENT_COLLEGE,
                Requests::STUDENT_UNIVERSITY,
            ])->nullable();
            $table->string('roll_no')->nullable();
            $table->string('class')->nullable();
            $table->string('section')->nullable();
            $table->longText('profile_card')->nullable();
            $table->string('descipline')->nullable();
            $table->string('designation')->nullable();
            $table->string('empolyee_id')->nullable();
            $table->foreignId('r_id')->nullable()->constrained('routes')->onDelete('cascade');
            $table->date('transport_start_time')->nullable();
            $table->date('transport_end_time')->nullable();
            $table->string('qualification')->nullable();
            $table->integer('btach_year')->nullable();
            $table->integer('degree_duration')->nullable();
            $table->string('cnic_no')->nullable();
            $table->string('cnic_front_image')->nullable();
            $table->string('cnic_back_image')->nullable();
            $table->string('relation')->nullable();
            $table->string('guardian_code')->nullable();
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
        Schema::dropIfExists('requests');
    }
}