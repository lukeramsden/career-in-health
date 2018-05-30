<?php

use Faker\Generator as Faker;

$factory->define(App\PrivateMessage::class, function (Faker $faker) {
    return [
        'user_id' => 2,
        'advert_id' => 1,
        'body' => $faker->text(500),
        'read' => false,
    ];
});
