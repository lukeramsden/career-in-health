<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertiserInvitesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('advertiser_invites', function (Blueprint $table)
		{
			$table->string('email')->primary()->unique()->index();
			$table->string('accept_code')->unique();
			$table->timestamp('last_reminded_at')->nullable();
			$table->integer('times_reminded')->unsigned()->default(0);
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
		Schema::dropIfExists('advertiser_invites');
	}
}
