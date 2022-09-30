<?php

namespace App\Http\Controllers;

use App\Hist_solicitud;
use App\Solicitud;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class SolicitudesHistController extends Controller
{
    private function fntCheckGroupCCEVyD(Solicitud $solicitud)
    {
        $groups_ccevyd = array(env('CCEVYD_GROUP_01'), env('CCEVYD_GROUP_02'), env('CCEVYD_GROUP_03'),
            env('CCEVYD_GROUP_04'), env('CCEVYD_GROUP_05'), env('CCEVYD_GROUP_06'),
            env('CCEVYD_GROUP_07'));

        //If 'solicitud' has value on gpo_nuevo...
        if (isset($solicitud->gpo_nuevo))
            if (in_array($solicitud->gpo_nuevo->name, $groups_ccevyd))
                return true;

        //If 'solicitud' has value on gpo_actual...
        if (isset($solicitud->gpo_actual))
            if (in_array($solicitud->gpo_actual->name, $groups_ccevyd))
                return true;

        return false;
    }

    public function show_sol_hist_list($solicitud_id)
    {
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_job_id = Auth::user()->job_id;
        $user_del_id = Auth::user()->delegacion_id;
        $user_del_name = Auth::user()->delegacion->name;

        $texto_log = 'User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id . '|Job:' . $user_job_id;

        // Se revisan los permisos...
        if ( Auth::user()->hasRole('admin_dspa') ) {
            // Si cuenta con los permisos...
            Log::info('Consultar Historial de cambios de estatus|' . $texto_log);

            $solicitud_actual       = Solicitud::where('id', $solicitud_id)->
                with('valija', 'movimiento', 'user', 'rechazo', 'status_sol', 'lote', 'delegacion', 'subdelegacion', 'gpo_actual', 'gpo_nuevo', 'resultado_solicitud')->first();
            $list_sol_historicas    = Hist_solicitud::where('solicitud_id', $solicitud_id)->
                with('valija', 'movimiento', 'user', 'rechazo', 'status_sol', 'lote', 'delegacion', 'subdelegacion', 'gpo_actual', 'gpo_nuevo')->get();
            //dd($list_sol_historicas);
            if ( !isset($solicitud_actual ) )
                return redirect('ctas')->with('message', 'No existe información de esa solicitud');
            elseif ( !isset($list_sol_historicas ) )
                return redirect('ctas')->with('message', 'No existe información histórica de esa solicitud');
            else {
                return view( 'ctas.solicitudes.list_sol_hist',
                    [
                        'solicitud_actual'      => $solicitud_actual,
                        'list_sol_historicas'   => $list_sol_historicas
                        ] );
                    }
                }
        else {
            Log::warning('Sin permiso-Consultar Historial de cambios de estatus|' . $texto_log);
            return redirect('ctas')->with('message', 'No tiene permitido consultar las solicitudes históricas');
        }
    }

}
