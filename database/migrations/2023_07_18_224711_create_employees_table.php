<?php

use App\Models\Employee;
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
            $table->id();
            $table->foreignId('request_id')->constrained('requests')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('house_no')->nullable();
            $table->string('street_no')->nullable();
            $table->string('town')->nullable();
            $table->string('city_id')->nullable();
            $table->string('pickup_address')->nullable();
            $table->string('pickup_city_id')->nullable();
            $table->string('lattitude')->nullable();
            $table->string('longitude')->nullable();
            $table->enum('status',[
                Employee::EMPLOYEE_STATUS_SCHOOL,
                Employee::EMPLOYEE_STATUS_COLLEGE,
                Employee::EMPLOYEE_STATUS_UNIVERSITY
            ]);
            $table->string('image')->nullable();
            $table->text('additional_detail')->nullable();
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
        Schema::dropIfExists('employees');
    }
}
