<?php

use App\Address;
use App\JobListing;
use Faker\Generator as Faker;

$factory->define(JobListing::class, function (Faker $faker)
{
	$min_salary = $faker->numberBetween(5000, 100000);
	$max_salary = $faker->numberBetween($min_salary, 250000);
	$datetime   = $faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now');
	$is_closed  = $faker->boolean(20);
	$closed_at  = $faker->dateTimeBetween($datetime, $endDate = 'now');
	return [
		'title'        => $faker->text(120),
		'description'  => $faker->text(3000),
		'job_role'     => $faker->numberBetween(1, count(\App\JobRole::$list)),
		'setting'      => $faker->numberBetween(1, count(JobListing::$settings)),
		'type'         => $faker->numberBetween(1, count(JobListing::$types)),
		'min_salary'   => $min_salary,
		'max_salary'   => $max_salary,
		'address_id'   => $faker->numberBetween(1, Address::all()->count()),
		'closed_at'    => $is_closed ? $closed_at : null,
		'close_reason' => $is_closed ? $faker->text(50) : null,
		'published'    => $is_closed ? true : $faker->boolean(80),
		'created_at'   => $datetime,
		'updated_at'   => $datetime,
		'last_edited'  => $datetime,
	];
});
