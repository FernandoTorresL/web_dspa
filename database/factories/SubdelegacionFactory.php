<?php

use Faker\Generator as Faker;

$factory->define(\App\Subdelegacion::class, function (Faker $faker) {
    return [
        'delegacion_id' => function () {
            return factory(App\Delegacion::class)->create()->id;
        },
        'num_sub' => $faker->randomElement([1, 2, 3, 4, 5]),
        'name' => $faker->toUpper($faker->country),
        'status' => $faker->randomElement([0, 1]),
    ];
});
