<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCvWorkExperienceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cv_work_experience', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cv_id')->unsigned();
            $table->foreign('cv_id')->references('id')->on('cvs');
            $table->string('job_title');
            $table->string('company_name');
            $table->text('description')->nullable();
            $table->string('location');
            $table->date('start_date');
            $table->date('end_date')->nullable();
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
        Schema::dropIfExists('cv_work_experience');
    }
}
