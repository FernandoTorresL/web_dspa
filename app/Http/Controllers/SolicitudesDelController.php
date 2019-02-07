<?php

namespace App\Http\Controllers;

use App\Solicitud;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class SolicitudesDelController extends Controller
{

    private function formatdate($pdate)
    {
        return Carbon::parse($pdate)->formatLocalized('%d de %B, %Y');
    }

    private function formatdatetime($pdatetime) {
        return Carbon:: parse($pdatetime)->formatLocalized('%d de %B, %Y. %H:%M');
    }

    //New function to show a sortable and simple table with pagination
    public function view_status()
    {
        $del = Auth::user()->delegacion_id;

        Log::info('Ver status solicitudes. User: ' . Auth::user()->name . '|Del:' . $del);

        if (Gate::allows('ver_status_solicitudes')) {
            if (Auth::user()->delegacion_id == 9) {
                $list_sol =
                    Solicitud::sortable()
                        ->with(['movimiento', 'rechazo', 'gpo_actual', 'gpo_nuevo', 'resultado_solicitud.rechazo_mainframe', 'lote'])
                        ->where('id', '>=', 3815)
                        ->latest()
                        ->paginate(50);
            }
            else {
                $list_sol =
                    Solicitud::sortable()
                        ->with(['movimiento', 'rechazo', 'gpo_actual', 'gpo_nuevo', 'resultado_solicitud.rechazo_mainframe', 'lote'])
                        ->where('delegacion_id', $del)
                        ->where('id', '>=', 3815)
                        ->latest()
                        ->paginate(20);
            }

            return view('ctas.solicitudes.delegacion_list', compact('list_sol'));
        }
        else {
            Log::info('Sin permiso-Consultar estatus solicitudes. Usuario:' . Auth::user()->name . '|Del:' . $del);

            abort(403,'No tiene permitido ver este listado');
        }

    }

    public function view_timeline($id)
    {
        setlocale(LC_TIME, 'es_ES');
        Carbon::setUtf8(false);
      
        $del = Auth::user()->delegacion_id;

        Log::info('Ver timeline solicitudes. User: ' . Auth::user()->name . '|Del:' . $del);

        if (Gate::allows('ver_timeline_solicitudes')) {
            $datos_timeline = Solicitud::find($id);

            $sin_dato = '--';

            $fecha_sol      = $this->formatdate($datos_timeline->fecha_solicitud_del);
            $mov_id_sol     = $datos_timeline->movimiento_id;
            $nombre_mov_sol = $datos_timeline->movimiento->name;
            $cuenta_sol     = $datos_timeline->cuenta;
            $matricula_sol  = $datos_timeline->matricula;
            $curp_sol       = $datos_timeline->curp;
            $nombre_sol     = $datos_timeline->primer_apellido . '-' . $datos_timeline->segundo_apellido . '-' . $datos_timeline->nombre;

            $fecha_sol_cap      = $this->formatdate($datos_timeline->created_at);
            $user_sol_cap       = $datos_timeline->user->name;
            $comment_sol_cap    = $datos_timeline->comment;

            $detalle_sol = '';
            if( $mov_id_sol == 1 || $mov_id_sol == 4 ) //--If tipo_movimiento is ALTA or CONNECT--}}
                $detalle_sol = $datos_timeline->gpo_nuevo->name;
            elseif( $mov_id_sol == 2 ) //--If tipo_movimiento is BAJA--}}
                $detalle_sol = $datos_timeline->gpo_actual->name;
            elseif( $mov_id_sol == 3 ) //--If tipo_movimiento is CAMBIO--}}
                $detalle_sol = $datos_timeline->gpo_actual->name . ' -> ' . $datos_timeline->gpo_nuevo->name;

            $titulo_sol = $nombre_mov_sol . ' - ' . $cuenta_sol . ' (' . $detalle_sol . ')';

            if( isset($datos_timeline->valija) ) {
                $num_of_ca_val  = $datos_timeline->valija->num_oficio_ca;
                $subt_val       = '';
                $fecha_val      = $this->formatdate($datos_timeline->valija->fecha_valija_del);
                $of_val         = $datos_timeline->valija->num_oficio_del;
                $del_val        = $datos_timeline->delegacion->name;

                $fecha_gestion      = $this->formatdate($datos_timeline->valija->fecha_recepcion_ca);
                $num_gestion        = $datos_timeline->valija->num_oficio_ca;
                $comment_gestion    = $datos_timeline->valija->comment;
            }
            else
            {
                $subt_val       = '(Solicitud sin valija)';
                $num_of_ca_val  = $fecha_val = $of_val = $del_val = $sin_dato;
                $fecha_gestion = $num_gestion = $comment_gestion  = $sin_dato;
            }

            //-- Setting the solicitud status --}}
            if( isset($datos_timeline->rechazo) ) {
                $color_sol_cap = 'text-danger';
                $rechazo_sol_cap = 'No procede. ' . $datos_timeline->rechazo->full_name;
            }
            else {
                $color_sol_cap = '';
                $rechazo_sol_cap = $sin_dato;
            }

            if( isset($datos_timeline->lote) ) {
                $fecha_lote     = $this->formatdate($datos_timeline->lote->fecha_oficio_lote);
                $num_lote       = $datos_timeline->lote->num_lote;
                $comment_lote   = $datos_timeline->lote->comment;
            }
            else {
                $fecha_lote     = $num_lote = $comment_lote = $sin_dato;
            }

            if( isset($datos_timeline->resultado_solicitud) ) //--If solicitud has a response ... --}}
            {
                $nombre_resp    = $datos_timeline->resultado_solicitud->name;
                $fecha_resp     = $this->formatdatetime($datos_timeline->resultado_solicitud->resultado_lote->attended_at);
                $comment_resp   = $datos_timeline->resultado_solicitud->comment;

                if( isset($datos_timeline->resultado_solicitud->rechazo_mainframe) ) {

                    if( ($datos_timeline->resultado_solicitud->status == 1) ) {

                        $color_resp = 'text-warning';
                        $rechazo_resp = 'Pendiente. ';
                    }
                    else {
                        $color_resp     = 'text-danger';
                        $rechazo_resp   = 'No procede. ';
                    }

                    $cta_resp = $nombre_resp = $sin_dato;
                    $rechazo_resp = $rechazo_resp . $datos_timeline->resultado_solicitud->rechazo_mainframe->name;
                }
                else {
                    $cta_resp       = $datos_timeline->resultado_solicitud->cuenta;
                    $color_resp     = 'text-success';
                    $rechazo_resp   = 'Atendida.';
                }
            }
            else {
                $nombre_resp = $fecha_resp = $comment_resp = $cta_resp = $color_resp = $rechazo_resp = $sin_dato;
            }

            return view('ctas.solicitudes.timeline',
                compact(
                    'datos_timeline',
                    'titulo_sol', 'fecha_sol', 'mov_id_sol', 'nombre_mov_sol', 'cuenta_sol',
                    'matricula_sol', 'curp_sol', 'nombre_sol', 'detalle_sol', 'resultado_cta',
                    'num_of_ca_val', 'subt_val', 'fecha_val', 'of_val', 'del_val',
                    'fecha_gestion', 'num_gestion', 'comment_gestion',
                    'fecha_sol_cap', 'user_sol_cap', 'comment_sol_cap',
                    'color_sol_cap', 'rechazo_sol_cap',
                    'fecha_lote', 'num_lote', 'comment_lote',
                    'fecha_resp', 'cta_resp', 'nombre_resp', 'color_resp', 'rechazo_resp', 'comment_resp')
                );
        }
        else {
            Log::info('Sin permiso-Consultar timeline solicitudes. Usuario:' . Auth::user()->name . '|Del:' . $del);

            abort(403,'No tiene permitido ver este timeline');
        }
    }

    public function view_detail_status()
    {
        $del = Auth::user()->delegacion_id;

        Log::info('Ver status solicitudes. User: ' . Auth::user()->name . '|Del:' . $del);

        if (Gate::allows('ver_detail_status_solicitudes')) {
            if (Auth::user()->delegacion_id == 9) {
                $listado_solicitudes =
                    Solicitud::sortable()
                        ->with(['movimiento', 'rechazo', 'gpo_actual', 'gpo_nuevo', 'resultado_solicitud.rechazo_mainframe', 'lote'])
                        ->where('id', '>=', 3815)
                        ->latest()
                        ->paginate(50);
            }
            else {
                $listado_solicitudes =
                    Solicitud::sortable()
                        ->with(['movimiento', 'rechazo', 'gpo_actual', 'gpo_nuevo', 'resultado_solicitud.rechazo_mainframe', 'lote'])
                        ->where('delegacion_id', $del)
                        ->where('id', '>=', 3815)
                        ->latest()
                        ->paginate(20);
            }

            return view('ctas.solicitudes.listado_status', compact('listado_solicitudes'));
        }
        else {
            Log::info('Sin permiso-Consultar estatus solicitudes. Usuario:' . Auth::user()->name . '|Del:' . $del);

            abort(403,'No tiene permitido ver este listado');
        }

    }

}
