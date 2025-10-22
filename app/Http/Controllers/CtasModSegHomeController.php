<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CtasModSegHomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        $user = Auth::user();

        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_job_id = Auth::user()->job_id;
        $user_del_id = Auth::user()->delegacion_id;
        $user_del_name = Auth::user()->delegacion->name;

        $texto_log = 'User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id . '|Job:' . $user_job_id;

        Log::info('Visitando Ctas_Mod_Seg-Home ' . $texto_log);

        $primer_renglon = $user_del_name;

        if ( $user->hasRole('capturista_dspa') ) {

            $primer_renglon .= ' - ' . $user->job->name;

            return view('ctas_mod_seg.home_ctas_mod_seg', compact('primer_renglon', 'user_del_id') );
        }
        elseif ( $user->hasRole('capturista_modulo_seguimiento') )
            {
            $primer_renglon = env('OOAD') . ' ' . $primer_renglon;

            return view('ctas_mod_seg.home_ctas_mod_seg', compact('primer_renglon', 'user_del_id') );
        }
        else return "No estas autorizado a ver esta página";
    }
}
