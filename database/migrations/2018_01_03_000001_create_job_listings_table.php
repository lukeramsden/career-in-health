<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_listings', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('company_id');
            $table->integer('created_by_user_id');

            $table->string('title', 120)->nullable();
            $table->string('description', 3000)->nullable();
            $table->integer('job_role')->nullable();
            $table->integer('setting')->nullable();
            $table->integer('type')->nullable();
            $table->integer('min_salary')->nullable();
            $table->integer('max_salary')->nullable();
            $table->integer('address_id')->nullable();
            $table->string('postcode', 10)->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->boolean('published')->default(false);

            $table->integer('page_views')->default(0);
            $table->integer('search_impressions')->default(0);
            $table->integer('search_clicks')->default(0);
            $table->integer('search_conversions')->default(0);
            $table->integer('recommended_impressions')->default(0);
            $table->integer('recommended_clicks')->default(0);
            $table->integer('recommended_conversions')->default(0);

            // NOTE: this defaults to the current time in the DATABASE's tz, NOT the app's tz
			$table->timestamp('last_edited')->nullable()->useCurrent();

			$table->timestamp('closed_at')->nullable();
			$table->string('close_reason')->nullable();

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
        Schema::dropIfExists('job_listings');
    }
}
