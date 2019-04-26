<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class Reto_DSPA extends Controller
{
    public function home()
    {
        if (Gate::allows('ver_modulo_reto_dspa')) {
            //Get user
            $user = Auth::user();

            Log::info('Visitando Reto_DSPA-Home|User:' . $user->name );

            return view('reto_dspa.home_reto_dspa', [
                            'persona_reto_1' => env('PERSONA_RETO_1', 'Persona V'),
                            'persona_reto_2' => env('PERSONA_RETO_2', 'Persona W'),
                            'persona_reto_3' => env('PERSONA_RETO_3', 'Persona Y'),
                            'persona_reto_4' => env('PERSONA_RETO_4', 'Persona Z')
                ]);
        }
        else {
            Log::warning('Sin permiso-Ver Módulo Reto_DSPA|Usuario:' . Auth::user()->name );
            return redirect('/home')->with('message', 'Su rol o usuario no tiene permitido consultar éste módulo');
        }
    }

    public function todos()
    {
        if (Gate::allows('ver_modulo_reto_dspa')) {
            //Get user
            $user = Auth::user();

            Log::info('Visitando Reto_DSPA-Todos|User:' . $user->name );

            return view('reto_dspa.todos', [
                'persona_reto_1' => env('PERSONA_RETO_1', 'Persona V'),
                'persona_reto_2' => env('PERSONA_RETO_2', 'Persona W'),
                'persona_reto_3' => env('PERSONA_RETO_3', 'Persona Y'),
                'persona_reto_4' => env('PERSONA_RETO_4', 'Persona Z')
            ]);
        }
        else {
            Log::warning('Sin permiso-Ver Módulo Reto_DSPA-Todos|Usuario:' . Auth::user()->name );
            return redirect('/home')->with('message', 'Su rol o usuario no tiene permitido consultar éste módulo');
        }
    }
}
