<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('created_by_user_id');
            $table->string('name', 40)->unique();
            $table->string('headline', 80)->nullable();
            $table->string('location', 80)->nullable();
            $table->string('description', 1000)->nullable();
            $table->string('avatar_path')->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('contact_email', 80)->nullable();
            // stripe stuff here
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
        Schema::dropIfExists('companies');
    }
}
