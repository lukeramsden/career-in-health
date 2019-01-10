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
	return function ($x) use ($property) {
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

if (!function_exists('verified_badge'))
{
  function verified_badge($item)
  {
	if ($item instanceof \App\Company && $item->verified)
	  return '<span class="oi oi-circle-check verified-badge"></span>';

	return '';
  }
}

if (!function_exists('array_diff_recursive'))
{
  function array_diff_recursive($arr1, $arr2)
  {
	$outputDiff = [];

	foreach ($arr1 as $key => $value)
	{
	  //if the key exists in the second array, recursively call this function
	  //if it is an array, otherwise check if the value is in arr2
	  if (array_key_exists($key, $arr2))
	  {
		if (is_array($value))
		{
		  $recursiveDiff = array_diff_recursive($value, $arr2[$key]);

		  if (count($recursiveDiff))
		  {
			$outputDiff[$key] = $recursiveDiff;
		  }
		}
		elseif (!in_array($value, $arr2))
		{
		  $outputDiff[$key] = $value;
		}
	  }
	  //if the key is not in the second array, check if the value is in
	  //the second array (this is a quirk of how array_diff works)
	  elseif (!in_array($value, $arr2))
	  {
		$outputDiff[$key] = $value;
	  }
	}

	return $outputDiff;
  }
}