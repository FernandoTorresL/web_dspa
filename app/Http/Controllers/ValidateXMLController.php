<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ValidateXMLController extends Controller
{
    public function home()
    {
        $user = Auth::user();

        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_job_id = Auth::user()->job_id;
        $user_del_id = Auth::user()->delegacion_id;
        $user_del_name = Auth::user()->delegacion->name;

        $texto_log = 'User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id . '|Job:' . $user_job_id;

        Log::info('Visitando ValidateXML-Home ' . $texto_log);

        $primer_renglon = $user_del_name;

        if ( $user->hasRole('admin_dspa') ) {

            $primer_renglon .= ' - ' . $user->job->name;

            return view('validate_xml.home_val_xml', compact('primer_renglon', 'user_del_id') );
        }
        else {
            Log::warning('Sin permiso-Ver Validador XML Home|' . $texto_log);
            abort(403,'No tiene permitido ver este Módulo');
        }

    }
}
