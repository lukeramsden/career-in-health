<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrivateMessagesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('private_messages', function (Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id');
			$table->integer('employee_id');
			$table->enum('direction', ['to_company', 'to_employee']);
			$table->integer('job_listing_id');
			$table->string('body', 1000);
			$table->boolean('read')->default(0);
			$table->timestamp('read_at')->nullable();
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
		Schema::dropIfExists('private_messages');
	}
}
