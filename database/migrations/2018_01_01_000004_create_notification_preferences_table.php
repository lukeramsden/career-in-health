<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationPreferencesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notification_preferences', function (Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->foreign('id')->references('id')->on('users')->onDelete('cascade');

			$table->boolean('email_promotions')->default(false);
			$table->boolean('email_private_message')->default(false);
			$table->boolean('email_listing_application')->default(false);
			$table->boolean('email_analytics')->default(false);

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
		Schema::dropIfExists('notification_preferences');
	}
}
