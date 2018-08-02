<?php

use App\JobListing;
use Faker\Generator as Faker;

$factory->define(JobListing::class, function (Faker $faker)
{
	$min_salary = $faker->numberBetween(0, 20000);
	$max_salary = $faker->numberBetween($min_salary, 250000);
	return [
		'title'       => $faker->text(120),
		'description' => $faker->text(3000),
		'job_role'    => $faker->numberBetween(1, count(\App\JobRole::$list)),
		'setting'     => $faker->numberBetween(1, count(JobListing::$settings)),
		'type'        => $faker->numberBetween(1, count(JobListing::$types)),
		'min_salary'  => $min_salary,
		'max_salary'  => $max_salary,
		'address_id'  => $faker->numberBetween(1, 5),
	];
});