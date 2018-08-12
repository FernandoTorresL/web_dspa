<?php

use Faker\Generator as Faker;

$factory->define(\App\Delegacion::class, function (Faker $faker) {
    return [
        'entidad_imss' => $faker->randomNumber(2),
        'ciz' => $faker->randomElement([1, 2, 3]),
        'descripcion' => $faker->word,
        'status' => $faker->randomElement([0, 1]),
    ];
});
