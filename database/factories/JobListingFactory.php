<?php

use App\Address;
use App\JobListing;
use Faker\Generator as Faker;

$factory->define(JobListing::class, function (Faker $faker)
{
	$min_salary = $faker->numberBetween(5000, 20000);
	$max_salary = $faker->numberBetween($min_salary, 250000);
	$datetime   = $faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now');
	return [
		'title'       => $faker->text(120),
		'description' => $faker->text(3000),
		'job_role'    => $faker->numberBetween(1, count(\App\JobRole::$list)),
		'setting'     => $faker->numberBetween(1, count(JobListing::$settings)),
		'type'        => $faker->numberBetween(1, count(JobListing::$types)),
		'min_salary'  => $min_salary,
		'max_salary'  => $max_salary,
		'address_id'  => $faker->numberBetween(1, Address::all()->count()),
		'created_at'  => $datetime,
		'updated_at'  => $datetime,
		'last_edited' => $datetime,
	];
});
