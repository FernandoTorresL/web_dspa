<?php

use Faker\Generator as Faker;

$factory->define(\App\Delegacion::class, function (Faker $faker) {
    return [
        'ciz' => $faker->randomElement([1, 2, 3]),
        'name' => $faker->toUpper($faker->country),
        'status' => $faker->randomElement([0, 1]),
    ];
});
