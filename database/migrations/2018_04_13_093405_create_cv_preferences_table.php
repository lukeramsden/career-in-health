<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCvPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cv_preferences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('job_type_id')->nullable();
            $table->integer('setting')->nullable();
            $table->integer('type')->nullable();
            $table->double('salary_number')->nullable();
            $table->integer('salary_type')->nullable();
            $table->boolean('willing_to_relocate')->nullable();
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
        Schema::dropIfExists('cv_preferences');
    }
}
