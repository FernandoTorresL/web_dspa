<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class SearchSolicitudesController extends Controller
{
    public function home()
    {
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_job_id = Auth::user()->job_id;
        $user_del_id = Auth::user()->delegacion_id;

        $texto_log = ' User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id . '|Job:' . $user_job_id;

        if (Gate::allows('ver_buscar_cta')) {
//            $solicitudes = Solicitud::sortable()
//                ->with(['valija', 'valija_oficio', 'delegacion', 'subdelegacion', 'movimiento', 'rechazo', 'grupo1', 'grupo2', 'lote', 'resultado_solicitud'])
//                ->where('solicitudes.id', '>=', 3815);
//
//            if ($user_del_id == 9) {
//                if ($user_job_id == 12)
//                    $solicitudes = $solicitudes
//                        ->where('valijas.origen_id', 12)
//                        ->latest()
//                        ->paginate(25);
//                else
//                    $solicitudes = $solicitudes
//                        ->latest()
//                        ->paginate(50);
//            }
//            else
//                $solicitudes = $solicitudes
//                    ->where('solicitudes.delegacion_id', $user_del_id)
//                    ->latest()
//                    ->paginate(20);

            Log::info('Ver Home-Buscar Solicitudes' . $texto_log);
            return view('ctas.solicitudes.home_search'
                );
        }
        else {
            Log::warning('Sin permisos-Home Buscar Solicitudes' . $texto_log);
            return redirect('ctas')->with('message', 'No tiene permitido Buscar Solicitudes.');
        }

    }
}
