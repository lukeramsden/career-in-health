<?php

namespace App\Console\Commands;

use App\Events\CreatedPrivateMessage;
use App\PrivateMessage;
use App\User;
use Illuminate\Console\Command;

class TestEvent extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'test:event';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Test event';

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
	 * @throws \Exception
	 */
	public function handle()
	{
		/** @var User $user */
//		$user = User::findOrFail((int)$this->ask('User ID', 1));

//    	if($user->isEmployee())
//    		$message = PrivateMessage
//				::whereEmployeeId($user->userable_id)
//				->inRandomOrder()
//				->first();
//
//		if($user->isValidCompany())
//			$message = PrivateMessage
//				::whereCompanyId($user->userable->company_id)
//				->inRandomOrder()
//				->first();

		/** @var PrivateMessage $message */
		$message = PrivateMessage
			::findOrFail((int)$this->ask('PM ID', 1));

		if (isset($message))
			broadcast(new CreatedPrivateMessage($message))->toOthers();
	}
}
