<?php

namespace App\Http\Controllers;

use App\Hist_solicitud;
use App\Http\Requests\CreateSolicitudChangingStatus;
use App\Solicitud;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SolChangeStatusController extends Controller
{

    public function change_estatus(CreateSolicitudChangingStatus $request, $changing_sol_id)
    {
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_del_id = Auth::user()->delegacion_id;
        $texto_log = '|User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id;

        Log::info('Cambiando Estatus Solicitud Nivel Central' . '|ID:' . $changing_sol_id . $texto_log);

        $solicitud_original = Solicitud::find($changing_sol_id);

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
            'user_id'               => $solicitud_original->user_id,
        ]);

        Log::info('Nva Solicitud Hist x cambio de status Nivel Central:' . $solicitud_hist->id . $texto_log);

        $solicitud = Solicitud::find($changing_sol_id);

        switch($request->input('action')) {
            case 'en_revision_dspa':
                $solicitud->status_sol_id = 1;
                $solicitud->rechazo_id    = NULL;
                $msg_type = 'message';
                $message = '¡La solicitud ha sido devuelta para nueva revisión de la DSPA!';
                break;
            break;

            case 'no_autorizar':
                $solicitud->rechazo_id    = $request->input('rechazo');
                //Si no se colocó causa de rechazo...
                if(!isset($solicitud->rechazo_id)) 
                {
                    $msg_type = 'error';
                    $message = 'No se colocó causa de rechazo';
                    return redirect('ctas/solicitudes/' . $solicitud->id)->with($msg_type, $message);
                }
                $solicitud->status_sol_id = 3;
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

        Log::info('Cambio de status a ' . $solicitud->status_sol_id . ' en solicitud NC|ID:' . $solicitud->id . $texto_log);

        return redirect('ctas/solicitudes/' . $solicitud->id)->with($msg_type, $message);
    }

}
