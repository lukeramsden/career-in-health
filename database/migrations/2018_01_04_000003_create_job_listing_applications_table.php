<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobListingApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_listing_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->nullable()->unsigned();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->integer('job_listing_id')->nullable()->unsigned();
            $table->foreign('job_listing_id')->references('id')->on('job_listings');
            $table->string('custom_cover_letter', 3000)->nullable();
            $table->integer('status')->nullable();
            // NOTE: this defaults to the current time in the DATABASE's tz, the app's tz
            $table->timestamp('last_edited')->nullable()->useCurrent();
            $table->timestamps();
            $table->unique(['employee_id', 'job_listing_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_listing_applications');
    }
}
