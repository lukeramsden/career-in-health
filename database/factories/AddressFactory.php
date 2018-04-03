<?php

use App\Models\Address;
use Faker\Generator as Faker;

$factory->define(Address::class, function (Faker $faker) {
    return [
        'name' => $faker->streetName,
        'town' => $faker->numberBetween(1, 30000)
    ];
});
