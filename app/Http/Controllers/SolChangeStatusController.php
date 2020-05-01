<?php

namespace App\Http\Controllers;

use App\Group;
use App\Hist_solicitud;
use App\Http\Requests\CreateSolicitudChangingStatus;
use App\Movimiento;
use App\Rechazo;
use App\Solicitud;
use App\Subdelegacion;
use App\Valija;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class SolChangeStatusController extends Controller
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

    public function change_estatus(CreateSolicitudChangingStatus $request, $changing_sol_id)
    {
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_del_id = Auth::user()->delegacion_id;
        $texto_log = '|User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id;

        Log::info('Cambiando Estatus Solicitud Nivel Central' . '|ID:' . $changing_sol_id . $texto_log);

        $solicitud_original = Solicitud::find($changing_sol_id);
        // $archivo = $solicitud_original->archivo;
        //$archivo_editado = $solicitud_original->file('archivo');
        //$nombre_archivo = $archivo_editado->getClientOriginalName();
        //$archivo_editado = $request->archivo->getClientOriginalName();
        //dd($nombre_archivo);
        $solicitud_hist = Hist_solicitud::create([
            'solicitud_id'          => $solicitud_original->id,
            'valija_id'             => $solicitud_original->valija_id,
            'fecha_solicitud_del'   => $solicitud_original->fecha_solicitud_del,
            'lote_id'               => $solicitud_original->lote_id,
            'delegacion_id'         => $solicitud_original->delegacion_id,
            'subdelegacion_id'      => $solicitud_original->subdelegacion_id,
            'nombre'                => $solicitud_original->nombre,
            'primer_apellido'       => $solicitud_original->primer_apellido,
            'segundo_apellido'      => $solicitud_original->segundo_apellido,
            'matricula'             => $solicitud_original->matricula,
            'curp'                  => $solicitud_original->curp,
            'cuenta'                => $solicitud_original->cuenta,
            'movimiento_id'         => $solicitud_original->movimiento_id,
            'gpo_nuevo_id'          => $solicitud_original->gpo_nuevo_id,
            'gpo_actual_id'         => $solicitud_original->gpo_actual_id,
            'status_sol_id'         => $solicitud_original->status_sol_id,
            'comment'               => $solicitud_original->comment,
            'rechazo_id'            => $solicitud_original->rechazo_id,
            'final_remark'          => $solicitud_original->final_remark,
            'archivo'               => $solicitud_original->archivo,
            //'archivo' => $solicitud_original->archivo  ->store('solicitudes/' . $user->delegacion_id, 'public'),
            'user_id'               => $solicitud_original->user_id,
        ]);

        Log::info('Nva Solicitud Hist x cambio de status Nivel Central:' . $solicitud_hist->id . $texto_log);

        $solicitud = Solicitud::find($changing_sol_id);
        // $delegacion = Subdelegacion::find($request->input('subdelegacion'))->delegacion->id;
        // $archivo = $request->file('archivo');

        //if ($request->input('valija') <> 0) {
        //    $solicitud->valija_id           = $request->input('valija');
        //}
        switch($request->input('action')) {
            case 'no_autorizar':
                $solicitud->status_sol_id = 3;
                $solicitud->rechazo_id    = $request->input('rechazo');
                $msg_type = 'error';
                $message = '¡La solicitud fue rechazada!';
            break;

            case 'autorizar':
                $solicitud->status_sol_id = 5;
                $solicitud->rechazo_id    = NULL;
                $msg_type = 'message';
                $message = '¡La solicitud ha sido autorizada y se ha eliminado la causa de rechazo!';
                break;

            default:
                Log::error('Error-Cambio de Status' . $texto_log);
                return redirect('ctas/solicitudes/' . $changing_sol_id)->with('error', 'Error - Comunicarlo al administrador.');
                break;
        }

        $solicitud->final_remark    = $request->input('final_remark');
        $solicitud->user_id         = $user_id;

        $solicitud->save();

        Log::info('Cambio de status de 4 a ' . $solicitud->status_sol_id . ' en solicitud NC|ID:' . $solicitud->id . $texto_log);

        return redirect('ctas/solicitudes/' . $solicitud->id)->with($msg_type, $message);
        // return redirect('ctas/solicitudes/' . $solicitud->id)->with('message', '¡Solicitud para ' . $solicitud->cuenta . ' creada exitosamente!');
    }

}
