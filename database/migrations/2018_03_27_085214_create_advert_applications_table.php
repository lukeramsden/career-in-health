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
            $table->integer('advert_id')->nullable()->unsigned();
            $table->foreign('advert_id')->references('id')->on('adverts');
            $table->string('custom_cover_letter');
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
        Schema::dropIfExists('advert_applications');
    }
}
