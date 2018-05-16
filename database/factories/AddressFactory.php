<?php

use App\Address;
use Faker\Generator as Faker;

$factory->define(Address::class, function (Faker $faker) {
    return [
        'name' => $faker->streetName,
        'location_id' => $faker->randomElement(\App\Location::getAllLocations())->id
    ];
});
