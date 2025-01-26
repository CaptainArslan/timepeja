<?php

use App\Models\Driver;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('organization_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('phone')->unique();
            $table->string('cnic')->unique();
            $table->longText('profile_picture')->nullable();
            $table->string('cnic_front_pic')->nullable();
            $table->string('cnic_back_pic')->nullable();
            $table->date('cnic_expiry_date')->nullable();
            $table->string('license_no')->unique();
            $table->string('license_no_front_pic')->nullable();
            $table->string('license_no_back_pic')->nullable();
            $table->date('license_expiry_date')->nullable();
            $table->string('otp')->nullable();
            $table->string('device_token')->nullable();
            $table->boolean('status')->default(Driver::STATUS_ACTIVE);
            $table->boolean('online_status')->default(Driver::OFFLINE);
            $table->json('address')->nullable();
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
        Schema::dropIfExists('drivers');
    }
}
