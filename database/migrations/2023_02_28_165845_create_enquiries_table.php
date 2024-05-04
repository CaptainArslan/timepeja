<?php




use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('u_id')->index('u_id')->nullable();
            $table->double('sender_id');
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->longText('message');
            $table->enum('user_type', ['student', 'employee', 'guardian', 'manager']);
            $table->enum('status', ['pending', 'active', 'replied']);
            $table->timestamps();
            $table->softDeletes();
            // $table->foreign('u_id')->references('id')->on('users')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enquiries');
    }
}
