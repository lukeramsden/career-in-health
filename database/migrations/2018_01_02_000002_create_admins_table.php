<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('avatar')->nullable();
            $table->timestamps();
        });

        if(env('APP_DEBUG', false)) {
			$user = factory(\App\User::class)
				->create([
					'email'             => 'admin@careerinhealth.co.uk',
					'password'          => Hash::make('letmein'), // TODO: better password
					'confirmed'         => 1,
					'confirmation_code' => null,
				]);
			$userable = new \App\Admin();
			$userable->first_name = 'Admin';
			$userable->save();
			$user->userable()->associate($userable);
			$user->save();
		}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
