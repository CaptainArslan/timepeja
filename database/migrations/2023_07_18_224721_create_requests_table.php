<?php

use App\Models\Request as Requests;
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
            $table->foreignId('organization_id')->constrained('organizations');

            $table->foreignId('parent_request_id')->nullable()->constrained('requests')->onDelete('cascade');
            $table->enum('type', [
                Requests::STUDENT,
                Requests::EMPLOYEE,
                Requests::STUDENT_GUARDIAN,
                Requests::EMPLOYEE_GUARDIAN,
            ]);
            $table->enum('student_type', [
                Requests::SCHOOL,
                Requests::COLLEGE,
                Requests::UNIVERSITY,
            ])->nullable();
            $table->enum('gender', [
                'male',
                'female',
                'other',
            ])->default('male');

            $table->string('name');
            $table->string('phone');
            $table->foreignId('passenger_id')->nullable()->constrained('passengers');
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('pickup_address')->nullable();
            $table->string('house_no')->nullable();
            $table->string('street_no')->nullable();
            $table->string('town')->nullable();
            $table->string('lattitude')->nullable();
            $table->string('longitude')->nullable();
            $table->foreignId('pickup_city_id')->nullable()->constrained('cities');
            $table->string('additional_detail')->nullable();
            $table->string('roll_no')->nullable();
            $table->string('class')->nullable();
            $table->string('section')->nullable();
            $table->integer('batch_year')->nullable();
            $table->integer('degree_duration')->nullable();

            $table->string('discipline')->nullable();
            $table->string('qualification')->nullable();

            $table->string('employee_comp_id')->nullable();
            $table->string('designation')->nullable();
            $table->longText('profile_card')->nullable();

            $table->string('cnic_no')->nullable();
            $table->string('cnic_front_image')->nullable();
            $table->string('cnic_back_image')->nullable();
            $table->string('relation')->nullable();
            $table->string('guardian_code')->nullable();

            $table->foreignId('route_id')->nullable()->constrained('routes')->onDelete('cascade');
            $table->date('transport_start_date')->nullable();
            $table->date('transport_end_date')->nullable();
            $table->string('created_by')->nullable();
            $table->integer('created_user_id')->nullable();
            $table->enum('status', [
                Requests::STATUS_PENDING,
                Requests::STATUS_APPROVED,
                Requests::STATUS_DISAPPROVED,
                Requests::STATUS_MEET_PERSONALLY,
            ])->default(Requests::STATUS_PENDING);
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
