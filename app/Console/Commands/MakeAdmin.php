<?php

namespace App\Console\Commands;

use App\Admin;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeAdmin extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'make:admin';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create admin user';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$this->info('Creating user...');

		$email    = $this->ask('Email');
		$password = $this->secret('Password');

		$user = new User();

		$user->email             = $email;
		$user->password          = Hash::make($password);
		$user->remember_token    = str_random(10);
		$user->confirmed         = true;
		$user->confirmation_code = null;

		$this->info('Creating userable...');

		$firstName = $this->ask('First Name');
		$lastName  = $this->ask('Last Name');

		$userable = new Admin([
			'first_name' => $firstName,
			'last_name'  => $lastName,
		]);

		$this->info('Saving...');

		$userable->save();
		$user->userable()->associate($userable);
		$user->save();

		$this->info('Success! Created user and userable models:');

		$userArray = array_except($user->toArray(), ['userable', 'confirmed', 'confirmation_code']);
		$this->table(array_keys($userArray), [$userArray]);
		$this->table(array_keys($userable->toArray()), [$userable->toArray()]);
	}
}
