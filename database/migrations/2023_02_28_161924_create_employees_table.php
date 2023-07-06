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
            $table->id();
            // $table->foreignId('request_id')->after('id')->constrained('requests')->onDelete('cascade');
            $table->string('name');
            $table->string('phone');
            $table->string('email');
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
        Schema::dropIfExists('employees');
    }
}
