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
            $table->integer('employee_id')->nullable()->unsigned();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->integer('advert_id')->nullable()->unsigned();
            $table->foreign('advert_id')->references('id')->on('adverts');
            $table->string('custom_cover_letter', 3000)->nullable();
            $table->integer('status')->nullable();
            $table->string('notes', 500)->nullable();
            // NOTE: this defaults to the current time in the DATABASE's tz, the app's tz
            $table->timestamp('last_edited')->nullable()->useCurrent();
            $table->timestamps();
            $table->unique(['employee_id', 'advert_id']);
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
