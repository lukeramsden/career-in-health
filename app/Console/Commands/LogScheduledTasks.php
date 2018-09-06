<?php

namespace App\Console\Commands;

use App\Console\ScheduleService;
use Carbon\Carbon;
use Cron\CronExpression;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;

class LogScheduledTasks extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'schedule:log';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Logs all scheduled tasks.';

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
		$commands = (new ScheduleService)->getCommands();
		$values = $commands
			->values()
			->map(function($val) {
				$val['next-execution'] = Carbon::instance($val['next-execution'])->diffForHumans();
				unset($val['expression']);
				return $val;
			});
		$this->table(array_keys($values->first()), $values);
	}
}
