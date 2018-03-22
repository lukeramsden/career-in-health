<?php

use App\Models\Advert;
use Faker\Generator as Faker;

$factory->define(Advert::class, function (Faker $faker) {
    $tempAdvert = new Advert();
    $min_salary = $faker->numberBetween(0, 20000);
    $max_salary = $faker->numberBetween($min_salary, 250000);
    return [
        'title' => $faker->text(120),
        'description' => $faker->text(3000),
        'job_type_id' => $faker->numberBetween(1, count(\App\Models\JobType::$list)),
        'setting' => $faker->numberBetween(1, count($tempAdvert->getSettings())),
        'type' => $faker->numberBetween(1, count($tempAdvert->getTypes())),
        'min_salary' => $min_salary,
        'max_salary' => $max_salary,
        'address_id' => $faker->numberBetween(1, 200),
    ];
});
