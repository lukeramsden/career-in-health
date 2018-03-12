<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adverts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('created_by_user_id');
            $table->string('title', 120)->nullable();
            $table->string('description', 3000)->nullable();
            $table->integer('role')->nullable();
            $table->integer('setting')->nullable();
            $table->integer('type')->nullable();
            $table->float('min_salary', 10, 2)->nullable();
            $table->float('max_salary', 10, 2)->nullable();
            $table->integer('location_id')->nullable();
            $table->string('postcode', 10)->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->integer('impressions')->default(0);
            $table->integer('clicks')->default(0);
            $table->integer('conversions')->default(0);
            $table->integer('status')->default(0);
            $table->timestamps();
        });

        App\Models\Location::LoadCSV();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adverts');
    }
}
