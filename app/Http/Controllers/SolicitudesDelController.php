<?php

namespace App\Http\Controllers;

use App\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class SolicitudesDelController extends Controller
{

    //New function to show a sortable and simple table with pagination
    public function search(Request $request)
    {
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_job_id = Auth::user()->job_id;
        $user_del_id = Auth::user()->delegacion_id;

        $search_word = $request->input('search_word');
        $texto_log = ' User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id . '|Job:' . $user_job_id;

        if ( Gate::allows('ver_status_solicitudes') ) {
            //Base query
            $solicitudes = Solicitud::sortable()
                ->with(['valija',
                    'valija_oficio',
                    'delegacion',
                    'subdelegacion',
                    'movimiento',
                    'grupo1',
                    'grupo2',
                    'lote',
                    'status_sol',
                    'resultado_solicitud'])
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') );

            if ( $user_del_id <> env('DSPA_USER_DEL_1') ) {
                //if is a 'Delegational' user, add delegacion_id to the query
                $solicitudes = $solicitudes->where('solicitudes.delegacion_id', $user_del_id);
            }

            if ( $user_job_id == env('DSPA_USER_JOB_ID_CCEVyD') ) {
                //if is a 'CCEVyD' user, add only some groups to the query
                $groups_ccevyd = array(env('CCEVYD_GROUP_01'), env('CCEVYD_GROUP_02'), env('CCEVYD_GROUP_03'),
                    env('CCEVYD_GROUP_04'), env('CCEVYD_GROUP_05'), env('CCEVYD_GROUP_06'),
                    env('CCEVYD_GROUP_07'));
                $solicitudes = $solicitudes->where(function ($list_where) use ($user_id) {
                    $list_where
                        ->where('solicitudes.gpo_actual_id', 4 )
                        ->orWhere('solicitudes.gpo_actual_id', 5 )
                        ->orWhere('solicitudes.gpo_actual_id', 8 )
                        ->orWhere('solicitudes.gpo_actual_id', 9 )
                        ->orWhere('solicitudes.gpo_actual_id', 10 )
                        ->orWhere('solicitudes.gpo_actual_id', 11 )
                        ->orWhere('solicitudes.gpo_actual_id', 24 )
                        ->orWhere('solicitudes.gpo_nuevo_id', 4 )
                        ->orWhere('solicitudes.gpo_nuevo_id', 5 )
                        ->orWhere('solicitudes.gpo_nuevo_id', 8 )
                        ->orWhere('solicitudes.gpo_nuevo_id', 9 )
                        ->orWhere('solicitudes.gpo_nuevo_id', 10 )
                        ->orWhere('solicitudes.gpo_nuevo_id', 11 )
                        ->orWhere('solicitudes.gpo_nuevo_id', 24 )
                        ->orWhere('solicitudes.user_id', $user_id )
                        ;
                });
            }

            if ( isset( $search_word ) && Gate::allows('ver_buscar_cta') ) {
                //And if there's a 'search word', add that word to the query and to the log
                $query = '%' . $search_word . '%';
                $solicitudes = $solicitudes->where(function ($list_where) use ($query) {
                    $list_where
                        ->where('solicitudes.cuenta', 'like', $query)
                        ->orWhere('solicitudes.primer_apellido', 'like', $query)
                        ->orWhere('solicitudes.segundo_apellido', 'like', $query)
                        ->orWhere('solicitudes.nombre', 'like', $query)
                        ->orWhere('solicitudes.matricula', 'like', $query)
                        ->orWhere('solicitudes.curp', 'like', $query);
                });
                $texto_log .= 'Buscando:' . $search_word;
            }

            //Finally add these instructions to any query
            $solicitudes = $solicitudes->latest()->paginate( env('ROWS_ON_PAGINATE') );

            Log::info('Buscar solicitudes ' . $texto_log);
            return view('ctas.solicitudes.delegacion_list',
                    ['solicitudes' => $solicitudes,
                    'search_word'      => $search_word]
                );
        }
        else {
            Log::warning('Sin permisos-Consultar status solicitudes' . $texto_log);
            return redirect('ctas')->with('message', 'No tiene permitido consultar status de solicitudes.');
        }
    }

    private function formatdate($pdate)
    {
        return Carbon::parse($pdate)->formatLocalized('%d de %B, %Y');
    }

    private function formatdatetime($pdatetime)
    {
        return Carbon:: parse($pdatetime)->formatLocalized('%d de %B, %Y %H:%M');
    }

    private function fdif_dias($pdatetime1, $pdatetime2)
    {
        return date_diff( $pdatetime1, $pdatetime2 )->format('%d día(s)');
    }

    public function view_timeline($id)
    {
        setlocale(LC_TIME, 'es-ES');
        Carbon::setUtf8(false);

        $del = Auth::user()->delegacion_id;

        Log::info('Ver timeline solicitudes. User: ' . Auth::user()->name . '|Del:' . $del);

        if ( Gate::allows('ver_timeline_solicitudes') || $del == 9 ) {

            $solicitud_t = Solicitud::find($id);

            //If there's not a solicitud with this id...
            if (!isset($solicitud_t)) {
                Log::warning('No existe el ID-Consultar timeline solicitudes. User:' . Auth::user()->name . '|Del:' . $del);

                abort(403, 'No existe el recurso solicitado');
            }

            $sin_dato = '--';

            $fecha_sol      = $this->formatdate($solicitud_t->fecha_solicitud_del);
            $mov_id_sol     = $solicitud_t->movimiento_id;
            $nombre_mov_sol = $solicitud_t->movimiento->name;
            $cuenta_sol     = $solicitud_t->cuenta;
            $matricula_sol  = $solicitud_t->matricula;
            $curp_sol       = $solicitud_t->curp;
            $nombre_sol     = $solicitud_t->primer_apellido . '-' . $solicitud_t->segundo_apellido . '-' . $solicitud_t->nombre;

            $fecha_sol_cap      = $this->formatdatetime($solicitud_t->created_at);
            $user_sol_cap       = $solicitud_t->user->name;
            $comment_sol_cap    = $solicitud_t->comment;

            $detalle_sol = '';
            if( $mov_id_sol == 1 || $mov_id_sol == 4 ) //--If tipo_movimiento is ALTA or CONNECT--}}
                $detalle_sol = $solicitud_t->gpo_nuevo->name;
            elseif( $mov_id_sol == 2 ) //--If tipo_movimiento is BAJA--}}
                $detalle_sol = $solicitud_t->gpo_actual->name;
            elseif( $mov_id_sol == 3 ) //--If tipo_movimiento is CAMBIO--}}
                $detalle_sol = $solicitud_t->gpo_actual->name . ' -> ' . $solicitud_t->gpo_nuevo->name;

            $titulo_sol = $nombre_mov_sol . ' - ' . $cuenta_sol . ' (' . $detalle_sol . ')';

            if( isset( $solicitud_t->valija ) ) {
                $num_of_ca_val  = $solicitud_t->valija->num_oficio_ca;
                $subt_val       = '';
                $fecha_val      = $this->formatdate($solicitud_t->valija->fecha_valija_del);
                $of_val         = $solicitud_t->valija->num_oficio_del;
                $del_val        = $solicitud_t->delegacion->name;

                $fecha_gestion      = $this->formatdate($solicitud_t->valija->fecha_recepcion_ca);
                $num_gestion        = $solicitud_t->valija->num_oficio_ca;
                $comment_gestion    = $solicitud_t->valija->comment;

                $date_diff_sol_val =
                    $this->fdif_dias( date_create($solicitud_t->fecha_solicitud_del), date_create($solicitud_t->valija->fecha_valija_del) );
                $date_diff_val_gestion =
                    $this->fdif_dias( date_create($solicitud_t->valija->fecha_valija_del), date_create($solicitud_t->valija->fecha_recepcion_ca) );
                $date_diff_gestion_cap =
                    $this->fdif_dias( date_create($solicitud_t->valija->fecha_recepcion_ca), $solicitud_t->created_at );
            }
            else
            {
                $subt_val           = '(Solicitud sin valija)';
                $num_of_ca_val      = $fecha_val            = $of_val       = $del_val  = $sin_dato;
                $fecha_gestion      = $num_gestion          = $comment_gestion          = $sin_dato;
                $date_diff_sol_val  = $date_diff_val_gestion= $date_diff_gestion_cap    = $sin_dato;
            }

            //-- Setting the solicitud status --}}
            if( isset($solicitud_t->rechazo) ) {
                $color_sol_cap = 'text-danger';
                $rechazo_sol_cap = 'No procede. ' . $solicitud_t->rechazo->full_name;
            }
            else {
                $color_sol_cap = '';
                $rechazo_sol_cap = $sin_dato;
            }

            if( isset($solicitud_t->lote) ) {
                $fecha_lote     = $this->formatdate($solicitud_t->lote->fecha_oficio_lote);
                $num_lote       = $solicitud_t->lote->num_lote;
                $comment_lote   = $solicitud_t->lote->comment;
                $date_diff_cap_lote =
                    $this->fdif_dias( $solicitud_t->created_at, date_create($solicitud_t->lote->fecha_oficio_lote) );
            }
            else {
                $fecha_lote = $num_lote = $comment_lote = $date_diff_cap_lote = $sin_dato;
            }

            //--If solicitud has a response ... --}}
            if( isset($solicitud_t->resultado_solicitud) ) {
                $nombre_resp    = $solicitud_t->resultado_solicitud->name;
                $fecha_resp     = $this->formatdatetime($solicitud_t->resultado_solicitud->resultado_lote->attended_at);
                $comment_resp   = $solicitud_t->resultado_solicitud->comment;
                $date_diff_lote_resp =
                    $this->fdif_dias( date_create($solicitud_t->lote->fecha_oficio_lote), date_create($solicitud_t->resultado_solicitud->resultado_lote->attended_at) );

                if( isset($solicitud_t->resultado_solicitud->rechazo_mainframe) ) {

                    if( ($solicitud_t->resultado_solicitud->status == 1) ) {
                        $color_resp = 'text-warning';
                        $rechazo_resp = 'Pendiente. ';
                    }
                    else {
                        $color_resp     = 'text-danger';
                        $rechazo_resp   = 'No procede. ';
                    }

                    $cta_resp = $nombre_resp = $sin_dato;
                    $rechazo_resp = $rechazo_resp . $solicitud_t->resultado_solicitud->rechazo_mainframe->name;
                }
                else {
                    $cta_resp       = $solicitud_t->resultado_solicitud->cuenta;
                    $color_resp     = 'text-success';
                    $rechazo_resp   = 'Atendida.';
                }
            }
            else {
                $nombre_resp = $fecha_resp = $comment_resp = $cta_resp = $color_resp = $rechazo_resp = $sin_dato;
                $date_diff_lote_resp = $sin_dato;
            }

            return view('ctas.solicitudes.timeline',
                compact(
                    'solicitud_t',
                    'titulo_sol', 'fecha_sol', 'mov_id_sol', 'nombre_mov_sol', 'cuenta_sol',
                    'matricula_sol', 'curp_sol', 'nombre_sol', 'detalle_sol',
                    'num_of_ca_val', 'subt_val', 'fecha_val', 'of_val', 'del_val',
                    'fecha_gestion', 'num_gestion', 'comment_gestion',
                    'fecha_sol_cap', 'user_sol_cap', 'comment_sol_cap', 'color_sol_cap', 'rechazo_sol_cap',
                    'fecha_lote', 'num_lote', 'comment_lote',
                    'fecha_resp', 'cta_resp', 'nombre_resp', 'color_resp', 'rechazo_resp', 'comment_resp',
                    'date_diff_sol_val', 'date_diff_val_gestion', 'date_diff_gestion_cap', 'date_diff_cap_lote', 'date_diff_lote_resp')
                );
        }
        else {
            Log::info('Sin permiso-Consultar timeline solicitudes. Usuario:' . Auth::user()->name . '|Del:' . $del);

            abort(403,'No tiene permitido ver este timeline');
        }
    }

}
