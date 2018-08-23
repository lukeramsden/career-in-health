<?php

if (!function_exists('ajax'))
{
	function ajax()
	{
		return app('request')->ajax();
	}
}

if (!function_exists('returns'))
{
	/**
	 * @param string|null $property
	 *
	 * @return callable
	 */
	function returns(string $property = null)
	{
		return function ($x) use ($property)
		{
			return object_get($x, $property);
		};
	}
}

if (!function_exists('map_to'))
{
	function map_to(array $array, string $property)
	{
		return array_map(returns($property), $array);
	}
}