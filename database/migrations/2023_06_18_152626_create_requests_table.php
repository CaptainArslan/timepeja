<?php

use App\Models\Requests;
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
            $table->foreignId('passenger_id')->nullable()->constrained('passengers')->onDelete('cascade');
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->string('roll_no')->nullable();
            $table->string('class')->nullable();
            $table->string('section')->nullable();
            $table->longText('profile_card')->nullable();
            $table->string('descipline')->nullable();
            $table->string('designation')->nullable();
            $table->string('empolyee_comp_id')->nullable();
            $table->foreignId('route_id')->nullable()->constrained('routes')->onDelete('cascade');
            $table->date('transport_start_time')->nullable();
            $table->date('transport_end_time')->nullable();
            $table->string('qualification')->nullable();
            $table->integer('batch_year')->nullable();
            $table->integer('degree_duration')->nullable();
            $table->enum('type', [
                Requests::REQUEST_TYPE_STUDENT,
                Requests::REQUEST_TYPE_EMPLOYEE,
                Requests::REQUEST_TYPE_GUARDIAN,
            ]);
            $table->enum('status', [
                Requests::REQUEST_STATUS_PENDING,
                Requests::REQUEST_STATUS_APPROVE,
                Requests::REQUEST_STATUS_DISAPPROVE,
                Requests::REQUEST_STATUS_MEET_PEROSONALLY,
            ])->default(Requests::REQUEST_STATUS_PENDING);
            // $table->string('name')->nullable();
            // $table->string('phone')->nullable();
            // $table->string('email')->nullable();
            // $table->string('house_no')->nullable();
            // $table->string('street_no')->nullable();
            // $table->string('town')->nullable();
            // $table->string('additional_detail')->nullable();
            // $table->foreignId('city_id')->constrained('cities');
            // $table->string('pickup_address')->nullable();
            // $table->foreignId('pickup_city_id')->constrained('cities');
            // $table->string('lattitude')->nullable();
            // $table->string('longitude')->nullable();

            // $table->enum('sub_type', [
            //     Requests::STUDENT_SCHOOL,
            //     Requests::STUDENT_COLLEGE,
            //     Requests::STUDENT_UNIVERSITY,
            //     Requests::EMPLOYEE,
            //     Requests::GUARDIAN,
            // ])->nullable();

            // $table->string('cnic_no')->nullable();
            // $table->string('cnic_front_image')->nullable();
            // $table->string('cnic_back_image')->nullable();
            // $table->string('relation')->nullable();
            // $table->string('guardian_code')->nullable();
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
