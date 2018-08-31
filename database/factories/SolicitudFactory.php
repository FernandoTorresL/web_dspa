<?php

use Faker\Generator as Faker;

$factory->define(\App\Solicitud::class, function (Faker $faker) {
    return [
        'valija_id' => 0,
        'fecha_solicitud_del' => $faker->dateTimeThisDecade('now'),
        'lote_id' => 0,
        'delegacion_id' => random_int(1, 40),
        'subdelegacion_id' => random_int(1, 180),
        'nombre' => $faker->toUpper(($faker->name)),
        'primer_apellido' => $faker->toUpper(($faker->lastName)),
        'segundo_apellido' => $faker->toUpper(($faker->lastName)),
        'matricula' => $faker->unique()->randomNumber(8),
        'curp' => $faker->toUpper($faker->regexify('/^[A-Z]{1}(A|E|I|O|U)[A-Z]{2}\d{6}[HM](AS|BC|BS|CC|CH|CL|CM|CS|DF|DG|GR|GT|HG|JC|MC|MN|MS|NE|NL|NT|OC|PL|QR|QT|SL|SP|SR|TC|TL|TS|VZ|YN|ZS)[A-Z]{3}\w{1}\d{1}')),
        'cuenta' => $faker->toUpper($faker->regexify('/^[A-Z]{1}(A|E|I|O|U)[A-Z]{2}\d{2}')),
        'movimiento_id' => random_int(1, 3),
        'gpo_nuevo_id' => random_int(1, 19),
        'gpo_actual_id' => random_int(1, 19),
        'comment' => $faker->realText(random_int(20, 50)),
        'causa_rechazo_id' => 0,
        'archivo' => 'https://picsum.photos/300/300?image=' . mt_rand(1,1000),
    ];
});
