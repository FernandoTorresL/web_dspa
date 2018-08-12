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
        'delegacion_id' => $faker->randomElement([1, 2, 3,4, 5, 6, 7, 8, 9]),
        'name' => $faker->name,
        'username' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'avatar' => 'https://picsum.photos/300/300?image=' . mt_rand(1,1000),
    ];
});

$factory->define(App\Message::class, function (Faker $faker) {
    return [
        'content' => $faker->realText(random_int(20, 191)),
        'image' => 'https://picsum.photos/600/338?image=' . mt_rand(1,1000)
    ];

});
