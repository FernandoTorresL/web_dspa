<?php

use Faker\Generator as Faker;

$factory->define(\App\Delegacion::class, function (Faker $faker) {
    return [
        'entidad_imss' => random_int(1, 40),
        'ciz' => $faker->randomElement([1, 2, 3]),
        'descripcion' => $faker->country,
        'status' => $faker->randomElement([0, 1]),
    ];
});
