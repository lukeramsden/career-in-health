<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyUserInvitesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_user_invites', function (Blueprint $table)
		{
			$table->string('email')->primary()->unique()->index();
			$table->string('accept_code')->unique();
			$table->integer('company_id')->unsigned();
			$table->foreign('company_id')->references('id')->on('companies');
			$table->integer('invited_by_id')->unsigned();
			$table->foreign('invited_by_id')->references('id')->on('company_users');
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
		Schema::dropIfExists('company_user_invites');
	}
}
