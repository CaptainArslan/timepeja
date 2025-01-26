<?php

use App\Models\Route;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->string('name');
            $table->string('number');
            $table->json('from');
            $table->json('to');
            $table->json('way_points')->nullable();
            $table->boolean('status')->default(Route::STATUS_ACTIVE);
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
        Schema::dropIfExists('routes');
    }
}
