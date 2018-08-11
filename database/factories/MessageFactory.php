<?php

use Faker\Generator as Faker;

$factory->define(App\Message::class, function (Faker $faker) {
    return [
        'content' => $faker->realText(random_int(20, 191)),
        'image' => 'https://picsum.photos/600/338?image=' . mt_rand(0,1084)
    ];

});
