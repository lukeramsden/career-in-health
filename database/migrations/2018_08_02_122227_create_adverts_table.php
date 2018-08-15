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
			/**
			 * Core
			 */
            $table->increments('id');
			$table->integer('advertiser_id')->unsigned();
			$table->foreign('advertiser_id')->references('id')->on('advertisers');
			$table->boolean('active')->default(false);
			$table->timestamp('started_at')->useCurrent();
			$table->timestamp('expires_at')->useCurrent();
			$table->timestamps();

			/**
			 * Details
			 */
			$table->string('title');
			$table->string('body')->nullable();
			$table->string('image_path')->nullable();
			$table->string('links_to', 500)->nullable();
			$table->integer('location_id')->nullable()->unsigned();
   			$table->foreign('location_id')->references('id')->on('locations');

			/**
			 * Statistics
			 */
			$table->bigInteger('stat_views')->default(0);
			$table->bigInteger('stat_clicks')->default(0);

			/**
			 * Demographics
			 */
			$table->integer('dem_location_id')->nullable()->unsigned();
   			$table->foreign('dem_location_id')->references('id')->on('locations');
			$table->boolean('dem_location_any')->default(true);

			$table->integer('dem_job_role_id')->nullable()->unsigned();
   			$table->foreign('dem_job_role_id')->references('id')->on('job_roles');
			$table->boolean('dem_job_role_any')->default(true);

			$table->boolean('dem_will_relocate')->nullable();
        });
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
