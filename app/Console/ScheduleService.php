<?php

namespace App\Console;

use App\Console\Commands\PurgeOldNotifications;
use Cron\CronExpression;
use Illuminate\Console\Scheduling\Schedule;

class ScheduleService
{
	public function registerCommands(Schedule $schedule)
	{
		$schedule
			->command(PurgeOldNotifications::class)
			->daily()
			->evenInMaintenanceMode();
	}

	/**
	 * @return \Illuminate\Support\Collection
	 */
	public function getCommands()
	{
		$schedule = app(Schedule::class);

		$this->registerCommands($schedule);

		$scheduledCommands = collect($schedule->events())
			->map(function ($event)
			{
				$expression = CronExpression::factory($event->expression);

				return [
					'command'        => $event->command,
					'expression'     => $event->expression,
					'next-execution' => $expression->getNextRunDate(),
				];
			});

		return $scheduledCommands;
	}
}