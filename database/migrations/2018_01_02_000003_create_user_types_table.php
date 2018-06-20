<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('display_name');
        });

        DB::table('user_types')->insert(['name' => 'employee',     'display_name' => 'Employee']);
        DB::table('user_types')->insert(['name' => 'company_user', 'display_name' => 'Company User']);
        DB::table('user_types')->insert(['name' => 'admin',        'display_name' => 'Admin']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_types');
    }
}
