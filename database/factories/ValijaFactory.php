<?php

use Faker\Generator as Faker;

$factory->define(App\Valija::class, function (Faker $faker) {
    return [
        'origen_id' => '1',
        'num_oficio_ca' => $faker->unique()->randomNumber(6),
        'fecha_recepcion_ca' => $faker->dateTimeThisDecade('now'),
        'delegacion_id' => random_int(1, 34),
        'num_oficio_del' => $faker->randomNumber(4),
        'fecha_valija_del' => $faker->dateTimeThisDecade('now'),
        'rechazo_id' => null,
        'comment' => $faker->realText(random_int(20, 191)),
        'archivo' => $faker->imageUrl(300, 300, 'people'),
        'user_id' => 1
    ];
});
