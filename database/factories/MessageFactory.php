<?php

use Faker\Generator as Faker;

$factory->define(App\Message::class, function (Faker $faker) {
    return [
        'content' => $faker->realText(random_int(20, 191)),
        'image' => 'http://placeimg.com/600/338/any?'  . mt_rand(0,1000)
    ];

});
