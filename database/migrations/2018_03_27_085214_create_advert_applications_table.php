<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advert_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('advert_id')->nullable()->unsigned();
            $table->foreign('advert_id')->references('id')->on('adverts');
            $table->string('custom_cover_letter', 3000)->nullable();
            $table->integer('status')->nullable();
            $table->string('notes', 500)->nullable();
            // NOTE: this defaults to the current time in the DATABASE's tz, the app's tz
            $table->timestamp('last_edited')->nullable()->useCurrent();
            $table->timestamps();
            $table->unique(['user_id', 'advert_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advert_applications');
    }
}
