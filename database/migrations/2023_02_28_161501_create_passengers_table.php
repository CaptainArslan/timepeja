<?php




use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreatePassengersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passengers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('phone');
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at');
            $table->string('password');
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            // $table->enum('type', ['student','gaurdian','employee']);
            $table->string('unique_id')->unique();
            $table->string('gaurd_code')->unique();
            // $table->enum('register_type', ['regular', 'social'])->default('');
            $table->text('bio')->nullable();
            // $table->string('location')->nullable();
            // $table->string('lattutude')->nullable();
            // $table->string('longitude')->nullable();
            // $table->string('google')->nullable();
            // $table->string('google_id')->nullable();
            // $table->string('facebook')->nullable();
            // $table->string('facebook_id')->nullable();
            // $table->string('twitter')->nullable();
            // $table->string('twitter_id')->nullable();
            $table->longText('image')->nullable();
            // $table->string('token')->unique();
            $table->string('otp')->nullable();
            // $table->string('house_no');
            // $table->string('near_by')->nullable();
            // $table->unsignedBigInteger('c_id')->index('c_id')->nullable();
            // $table->string('street_no');
            // $table->string('town');
            $table->boolean('status')->default(true);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            // $table->foreign('c_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('passengers');
    }
}
