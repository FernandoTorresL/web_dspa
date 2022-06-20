<?php

namespace App\Http\Controllers;

use App\Hist_solicitud;
use App\Http\Requests\CreateSolicitudChangingStatus;
use App\Solicitud;
use App\Lote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SolChangeStatusController extends Controller
{

    public function change_estatus(CreateSolicitudChangingStatus $request, $id_sol_original)
    {
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_del_id = Auth::user()->delegacion_id;
        $texto_log = '|User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id;

        $sol_original = Solicitud::find($id_sol_original);

        $sol_nueva = Solicitud::find($id_sol_original);
        $sol_nueva->final_remark    = $request->input('final_remark');
        $sol_nueva->user_id         = $user_id;

        Log::info('Intento de cambio de Estatus Solicitud Nivel Central' . '|ID:' . $id_sol_original . $texto_log);

        switch($request->input('action')) {

            case 'enviar_a_correccion':
                $sol_nueva->status_sol_id = 2;
                $sol_nueva->rechazo_id    = $request->input('rechazo');
                $msg_type = 'warning';
                $message = '¡La solicitud ha sido devuelta a la delegación para correcciones!';
                break;

            case 'pedir_vobo':
                $sol_nueva->status_sol_id = 4;
                $sol_nueva->rechazo_id    = NULL;
                $msg_type = 'message';
                $message = '¡La solicitud ha sido enviada a CCEyVD para su VoBo!';
                break;

            case 'enviar_a_mainframe':
                $lote_id = Lote::where('status', 1)->orderBy( 'lotes.id', 'desc')->first();

                if( !isset($lote_id) )
                {
                    $msg_type = 'error';
                    $message = 'No lotes abiertos o hay más de un lote aún abierto';
                    return redirect('ctas/solicitudes/' . $sol_nueva->id)->with($msg_type, $message);
                }

                $sol_nueva->status_sol_id = 6;
                $sol_nueva->lote_id       = $lote_id->id;
                $sol_nueva->rechazo_id    = NULL;
                $msg_type = 'message';
                $message = '¡La solicitud ha sido asignada al lote '. $lote_id->num_lote . 'y está lista para envío a Mainframe!';
                break;

            case 'en_revision_dspa':
                $sol_nueva->status_sol_id = 1;
                $sol_nueva->final_remark    = $sol_original->final_remark . ' (Corregida)';
                $sol_nueva->rechazo_id    = NULL;
                $msg_type = 'message';
                $message = '¡La solicitud ha sido devuelta para nueva revisión de la DSPA!';
                break;

            case 'no_autorizar':
                $sol_nueva->rechazo_id    = $request->input('rechazo');
                //Si no se colocó causa de rechazo...
                if(!isset($sol_nueva->rechazo_id))
                {
                    $msg_type = 'error';
                    $message = 'No se colocó causa de rechazo';
                    return redirect('ctas/solicitudes/' . $sol_nueva->id)->with($msg_type, $message);
                }
                $sol_nueva->status_sol_id = 3;
                $msg_type = 'error';
                $message = '¡La solicitud fue rechazada!';
                break;

            case 'autorizar':
                $sol_nueva->status_sol_id = 5;
                $sol_nueva->rechazo_id    = NULL;
                $msg_type = 'message';
                $message = '¡La solicitud ha sido autorizada y se ha eliminado la causa de rechazo!';
                break;

            default:
                Log::error('Error-Cambio de Status' . $texto_log);
                return redirect('ctas/solicitudes/' . $id_sol_original)->with('error', 'Error - Comunicarlo al administrador.');
                break;
        }

        // All good so far, so create the historical record:
        $sol_hist = Hist_solicitud::create([
            'solicitud_id'          => $sol_original->id,
            'valija_id'             => $sol_original->valija_id,
            'fecha_solicitud_del'   => $sol_original->fecha_solicitud_del,
            'lote_id'               => $sol_original->lote_id,
            'delegacion_id'         => $sol_original->delegacion_id,
            'subdelegacion_id'      => $sol_original->subdelegacion_id,
            'nombre'                => $sol_original->nombre,
            'primer_apellido'       => $sol_original->primer_apellido,
            'segundo_apellido'      => $sol_original->segundo_apellido,
            'matricula'             => $sol_original->matricula,
            'curp'                  => $sol_original->curp,
            'cuenta'                => $sol_original->cuenta,
            'movimiento_id'         => $sol_original->movimiento_id,
            'gpo_nuevo_id'          => $sol_original->gpo_nuevo_id,
            'gpo_actual_id'         => $sol_original->gpo_actual_id,
            'status_sol_id'         => $sol_original->status_sol_id,
            'comment'               => $sol_original->comment,
            'rechazo_id'            => $sol_original->rechazo_id,
            'final_remark'          => $sol_original->final_remark,
            'archivo'               => $sol_original->archivo,
            'user_id'               => $sol_original->user_id,
        ]);

        Log::info('Nva Solicitud Hist x cambio de status Nivel Central:' . $sol_hist->id . $texto_log);

        $sol_nueva->save();

        Log::info('Cambio de status a ' . $sol_nueva->status_sol_id . ' en solicitud NC|ID:' . $sol_nueva->id . $texto_log);

        return redirect('ctas/solicitudes/' . $sol_nueva->id)->with($msg_type, $message);
    }

}
