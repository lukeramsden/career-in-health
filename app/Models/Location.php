<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
	/**
	 * Returns all locations that match type 'City' or 'Town'
	 *
	 * @return array
	 */
	public static function getAllLocations()
	{
		$locations = Location::select(['id', 'name', 'county'])
							 ->whereIn('type', ['City', 'Town'])
							 ->get();

		$locs = [];

		foreach ($locations as $loc)
		{
			$locs[] = (object)[
				'id'   => $loc->id,
				'name' => str_replace("'", '', $loc->name) . ' (' . $loc->county . ')',
			];
		}

		$locs = collect($locs)->unique('name')->all();

		return $locs;
	}

	/**
	 * Returns flat array of all unique counties
	 *
	 * @return string[]
	 */
	public static function getCounties()
	{
		return Location::distinct()->get(['county'])->pluck('county');
	}

	public static function loadCsv()
	{
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 10000);

		try
		{
			Location::where('id', '>', 0)->delete();
		} catch (\Exception $e)
		{
		}

		$csv = array_map('str_getcsv', file(base_path() . '/storage/app/public/uk-towns.csv'));

		$keys = $csv[0];
		unset($csv[0]);

		$count = 0;
		$temp  = [];

		foreach ($csv as $line)
		{
			$t = [];

			foreach ($keys as $k => $v)
			{
				$t[$v] = trim(explode(' / ', $line[$k])[0]);
			}

			unset($t['id']);
			$temp[] = $t;

			$count++;

			if ($count >= 900)
			{
				Location::insert($temp);
				unset($temp);

				$temp  = [];
				$count = 0;
			}
		}

		if (count($temp) > 0)
		{
			Location::insert($temp);
		}
	}

	public function mapUrl()
	{
		return "http://maps.google.com/maps?q=$this->latitude,$this->longitude";
	}

}
