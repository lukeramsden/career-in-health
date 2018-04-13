<?php

use App\Address;
use Faker\Generator as Faker;

$locations = \App\Models\Location::getAllLocations();
$factory->define(Address::class, function (Faker $faker) use($locations) {
    return [
        'name' => $faker->streetName,
        'town' => $faker->randomElement($locations)->id
    ];
});
