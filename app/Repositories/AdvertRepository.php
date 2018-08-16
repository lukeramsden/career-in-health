<?php

namespace App\Repositories;

use App\Advert;
use App\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AdvertRepository
{
	/*
	 * How many adverts to take
	 */
	const advertTake = 10;

	/**
	 * Get job-listing
	 * Stores "advertTake" number of adverts on first call, and returns one each time its called
	 *
	 * @throws \Exception
	 */
	public static function get()
	{
		/** @var bool $once */
		static $once = false;

		/** @var \Illuminate\Database\Eloquent\Collection $adverts */
		static $adverts;

		/** @var int $calls */
		static $calls = 0;

		if ($calls >= self::advertTake)
			throw new \Exception('Too many calls to ' . __CLASS__ . '::' . __FUNCTION__ . '()');

		if (!$once)
		{
			$once    = true;
			$adverts = collect([]);

			if (Auth::check()
				&& ($user = Auth::user())->isEmployee())
			{
				/** @var Employee $employee */
				$employee = $user->userable;

				// get adverts for user
				$q = Advert::whereActive(true);

				if ($employee->location_id !== null)
					$q = $q
						->where(function ($q) use ($employee)
						{
							/** @var Builder $q */
							$q->where('dem_location_any', true)
							  ->orWhere([
								  ['dem_location_any', '=', false],
								  ['dem_location_id', '=', $employee->location_id],
							  ]);
						});

				if ($employee->cv->preferences->job_role !== null)
					$q = $q
						->where(function ($q) use ($employee)
						{
							/** @var Builder $q */
							$q->where('dem_job_role_any', true)
							  ->orWhere([
								  ['dem_job_role_any', '=', false],
								  ['dem_job_role_id', '=', $employee->cv->preferences->job_role],
							  ]);
						});

				// if employee is not willing to relocate
				// filter out adverts in different areas where job-listing
				// has specified that user must relocate
				if ($employee->cv->preferences->willing_to_relocate !== true)
					$q = $q
						->where(function ($q) use ($employee)
						{
							/** @var Builder $q */
							$q->where('location_id', '=', $employee->location_id)
							  ->orWhere('dem_will_relocate', '=', false);
						});

				$adverts = $q->inRandomOrder()
							 ->take(self::advertTake)
							 ->get();
			}
			else
			{
				// get any job-listing
				$adverts =
					Advert
						::whereActive(true)
						->inRandomOrder()
						->take(self::advertTake)
						->get();
			}
		}

		$calls++;
		return $adverts->shift();
	}
}