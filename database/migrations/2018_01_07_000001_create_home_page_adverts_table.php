<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomePageAdvertsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('home_page_adverts', function (Blueprint $table)
		{
			/**
			 * Core
			 */
			$table->increments('id');
			$table->timestamps();

			/**
			 * Detail
			 */
			$table->string('image_path');
			$table->string('links_to', 500)->nullable();

			/**
			 * Statistics
			 */
			$table->bigInteger('stat_views')->default(0);
			$table->bigInteger('stat_clicks')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('home_page_adverts');
	}
}
