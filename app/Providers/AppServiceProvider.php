<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        View::share('contacto_mail', env('CONTACTO_MAIL'));
        View::share('contacto_conm1', env('CONTACTO_CONM1'));
        View::share('contacto_conm2', env('CONTACTO_CONM2'));
        View::share('contacto_ext', env('CONTACTO_EXT'));
        View::share('contacto_dir', env('CONTACTO_DIR'));
        View::share('contacto_div', env('CONTACTO_DIV'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
