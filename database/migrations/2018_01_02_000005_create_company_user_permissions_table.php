<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyUserPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
	{
		Schema::create('company_user_permissions', function (Blueprint $table)
		{
			$table->integer('company_id')->unsigned();
			$table->foreign('company_id')->references('id')->on('companies');
			$table->integer('company_user_id')->unsigned();
			$table->foreign('company_user_id')->references('id')->on('company_users');
			$table->index('company_id');

			$table->enum('permission_level', ['owner', 'manager', 'standard']);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_user_permissions');
    }
}
