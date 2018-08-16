<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'username' => $faker->toUpper($faker->regexify('/^[A-Z]{1}(A|E|I|O|U)[A-Z]{2}\d{6}[HM](AS|BC|BS|CC|CH|CL|CM|CS|DF|DG|GR|GT|HG|JC|MC|MN|MS|NE|NL|NT|OC|PL|QR|QT|SL|SP|SR|TC|TL|TS|VZ|YN|ZS)[A-Z]{3}\w{1}\d{1}')),
        'matricula' => $faker->unique()->randomNumber(8),
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'avatar' => 'https://picsum.photos/300/300?image=' . mt_rand(1,1000),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'status' => $faker->randomElement([0, 1]),
        'delegacion_id' => function () {
            return factory(App\Delegacion::class)->create()->id;
        },
        'job_id' => function () {
            return factory(App\Job::class)->create()->id;
        }
    ];
});

$factory->define(App\Message::class, function (Faker $faker) {
    return [
        'content' => $faker->realText(random_int(20, 191)),
        'image' => 'https://picsum.photos/600/338?image=' . mt_rand(1,1000)
    ];

});
