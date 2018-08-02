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

			$table->integer('advertiser_id')->unsigned();
			$table->foreign('advertiser_id')->references('id')->on('advertisers');

			$table->string('title')->nullable();
			$table->string('body')->nullable();
			$table->string('image_path')->nullable();

			$table->string('links_to', 500)->nullable();

			$table->boolean('active')->default(false);
			$table->timestamp('started_at')->nullable();
			$table->timestamp('expires_at')->nullable();

			$table->bigInteger('stat_views')->default(0);
			$table->bigInteger('stat_clicks')->default(0);

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
