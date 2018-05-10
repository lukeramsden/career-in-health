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
            $table->integer('job_role')->nullable();
            $table->integer('setting')->nullable();
            $table->integer('type')->nullable();
            $table->integer('min_salary')->nullable();
            $table->integer('max_salary')->nullable();
            $table->integer('address_id')->nullable();
            $table->string('postcode', 10)->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->integer('search_impressions')->default(0);
            $table->integer('search_clicks')->default(0);
            $table->integer('search_conversions')->default(0);
            $table->integer('recommended_impressions')->default(0);
            $table->integer('recommended_clicks')->default(0);
            $table->integer('recommended_conversions')->default(0);
            $table->integer('status')->default(0);
            $table->timestamp('last_edited')->nullable();
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
        Schema::dropIfExists('adverts');
    }
}
