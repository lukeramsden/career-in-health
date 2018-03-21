<?php

use App\Models\Advert;
use Faker\Generator as Faker;

$factory->define(Advert::class, function (Faker $faker) {
    $tempAdvert = new Advert();
    return [
        'title' => $faker->text(120),
        'description' => $faker->text(3000),
        'role' => $faker->numberBetween(1, count($tempAdvert->getRoles())),
        'setting' => $faker->numberBetween(1, count($tempAdvert->getSettings())),
        'type' => $faker->numberBetween(1, count($tempAdvert->getTypes())),
        'min_salary' => $faker->randomFloat(2, 0, 1000000),
        'max_salary' => $faker->randomFloat(2, 0, 1000000),
        'address_id' => $faker->numberBetween(1, 30000),
    ];
});
