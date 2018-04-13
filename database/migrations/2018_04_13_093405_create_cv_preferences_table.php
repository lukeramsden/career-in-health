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
            $table->integer('salary')->nullable();
            $table->boolean('willing_to_relocate');
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
