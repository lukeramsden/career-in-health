<?php

use App\Models\Address;
use Faker\Generator as Faker;

$factory->define(Address::class, function (Faker $faker) {
    return [
        'town' => $faker->numberBetween(1, 30000)
    ];
});
