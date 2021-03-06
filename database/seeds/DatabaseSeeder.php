<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Delegacion::class, 2)->create()->each(function (\App\Delegacion $delegacion) {

            factory(App\Subdelegacion::class)
                ->times(5)
                ->create([
                    'delegacion_id' => $delegacion->id,
                ]);
        });

        factory(App\User::class, 40)->create()->each(function (App\User $user) {

            factory(App\Message::class)
                ->times(4)
                ->create([
                    'user_id' => $user->id,
                ]);

            factory(App\Solicitud::class)
                ->times(9)
                ->create([
                    'user_id' => $user->id,
                ]);
        });

    }
}
