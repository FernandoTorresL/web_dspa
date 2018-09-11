<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        $response = $next($request);
        //If the status is not approved redirect to login
        if(Auth::check() && Auth::user()->status != '2'){

            Auth::logout();
            $request->session()->flash('alert-danger', 'Tu cuenta no está activa. Comunícate con el Administrador');
            return redirect('/login')->with('erro_login', 'Your error text');
        }
        return $response;
    }
}
