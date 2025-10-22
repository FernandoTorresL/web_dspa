<?php

namespace App\Http\Controllers;

use App\Movimiento;
use App\Rechazo;
use App\Solicitud;
use App\Subdelegacion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class SolCtasModSegController extends Controller
{
        public function home()
    {
        $user_name = Auth::user()->name;
        $user_del_id = Auth::user()->delegacion_id;
        $user_del_name = Auth::user()->delegacion->name;

        $texto_log = '|Usuario:' . $user_name . '|Del:' . $user_del_id;

        if (Gate::allows('capture_sol_mod_seg_nc') || Gate::allows('capture_sol_mod_seg_del') ) {
            Log::info('Capturar Solicitud de usuario para el Módulo de Seguimiento' . $texto_log);

            //Get the common information
            $movimientos = Movimiento::where('status', '<>', 0)->orderBy('name', 'asc')->get();
            //$gruposNuevo =  Group::whereBetween('status', [1, 2])->orderBy('name', 'asc')->get();
            //$gruposActual = Group::whereBetween('status', [1, 3])->orderBy('name', 'asc')->get();

            //Get the particular information
            if (Gate::allows('capture_sol_mod_seg_del')) {
                //$valijas = '';
                $subdelegaciones = Subdelegacion::where('delegacion_id', $user_del_id)->where('status', '<>', 0)->orderBy('num_sub', 'asc')->get();
                $rechazos = '';
            }
            elseif (Gate::allows('capture_sol_mod_seg_nc')) {
                //$valijas = Valija::with('delegacion')->where('status', '<>', 0)->orderBy('num_oficio_ca', 'desc')->get();
                $subdelegaciones = Subdelegacion::with('delegacion')->where('status', '<>', 0)->orderBy('id', 'asc')->get();
                $rechazos = Rechazo::all();
            }
        }
        else {
            Log::warning('Sin permiso-Capturar Solicitudes para el Módulo de Seguimiento' . $texto_log);
            return redirect('ctas')->with('message', 'No tiene permitido capturar solicitudes de usuario para el Módulo de Seguimiento.');
        }

        return view(
            'ctas_mod_seg.solicitudes.create', [
            'del_id' => $user_del_id,
            'del_name' => $user_del_name,
            //'valijas' => $valijas,
            'movimientos' => $movimientos,
            'subdelegaciones' => $subdelegaciones,
            //'gruposNuevo' => $gruposNuevo,
            //'gruposActual' => $gruposActual,
            'rechazos' => $rechazos,
        ]);
    }
}
