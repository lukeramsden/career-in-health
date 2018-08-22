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
			$table->boolean('active')->default(false);
			$table->integer('advertiser_id')->unsigned();
			$table->foreign('advertiser_id')->references('id')->on('advertisers');
			$table->integer('advertable_id')->nullable();
			$table->string('advertable_type')->nullable();
			$table->timestamp('started_at')->useCurrent();
			$table->timestamp('expires_at')->useCurrent();
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
