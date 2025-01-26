<?php

use App\Models\Manager;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('organization_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique();
            $table->string('password');
            $table->string('otp');
            $table->string('device_token')->nullable();
            $table->string('picture')->nullable();
            $table->longText('about')->nullable();
            $table->tinyInteger('status')->default(Manager::STATUS_ACTIVE);
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
        Schema::dropIfExists('managers');
    }
}
