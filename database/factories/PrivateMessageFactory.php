<?php

use Faker\Generator as Faker;

$factory->define(\App\PrivateMessage::class, function (Faker $faker) {
    return [
        'body' => $faker->text(500),
        'read' => false,
    ];
});
