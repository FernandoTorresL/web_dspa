<?php

namespace App\Http\Controllers;

use App\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use App\Http\Helpers\Helpers;

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
            $solicitudes =
                Solicitud::select('id', 'lote_id', 'created_at', 'delegacion_id', 'subdelegacion_id',
                    'cuenta', 'nombre', 'primer_apellido', 'segundo_apellido', 'movimiento_id',
                    'gpo_actual_id', 'gpo_nuevo_id', 'status_sol_id', 'matricula', 'curp')
                ->with(['delegacion:id,name',
                        'subdelegacion:id,name,num_sub',
                        'movimiento:id,name',
                        'lote:id,num_lote',
                        'gpo_actual:id,name',
                        'gpo_nuevo:id,name',
                        'status_sol:id,name,description',
                        'resultado_solicitud:id,solicitud_id,cuenta'])
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
            //$solicitudes = $solicitudes->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') );
            $solicitudes = $solicitudes->latest()->paginate( env('ROWS_ON_PAGINATE') );

            Log::info('Buscar solicitudes ' . $texto_log);
            return view('ctas.solicitudes.delegacion_list',
                    ['solicitudes' => $solicitudes,
                    'search_word'  => $search_word]
                );
        }
        else {
            Log::warning('Sin permisos-Consultar status solicitudes' . $texto_log);
            return redirect('ctas')->with('message', 'No tiene permitido consultar status de solicitudes.');
        }
    }

    public function view_timeline($id)
    {
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_job_id = Auth::user()->job_id;
        $user_del_id = Auth::user()->delegacion_id;
        $user_del_name = Auth::user()->delegacion->name;

        $texto_log = 'User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id . '|Job:' . $user_job_id;
        Log::info('Ver Timeline solicitud|' . $texto_log);

        if ( Gate::allows('ver_timeline_solicitudes') || $del == 9 ) {

            //$solicitud_t = Solicitud::with(['hist_solicitudes'])->find($id);
            $solicitud_t = Solicitud::find($id);

            //If there's not a solicitud with this id...
            if (!isset($solicitud_t)) {
                Log::warning('No existe el ID-Consultar timeline solicitudes. User:' . Auth::user()->name . '|Del:' . $del);

                abort(403, 'No existe el recurso solicitado');
            }

            $sol_gpo_detail = ' (';
            //--If tipo_movimiento is ALTA or CONNECT--}}
            if( $solicitud_t->movimiento_id == 1 || $solicitud_t->movimiento_id == 4 )
                $sol_gpo_detail = $sol_gpo_detail . $solicitud_t->gpo_nuevo->name;
            //--If tipo_movimiento is BAJA--}}
            elseif( $solicitud_t->movimiento_id == 2 )
                $sol_gpo_detail = $sol_gpo_detail . $solicitud_t->gpo_actual->name;
            //--If tipo_movimiento is CAMBIO--}}
            elseif( $solicitud_t->movimiento_id == 3 )
                $sol_gpo_detail = $sol_gpo_detail . $solicitud_t->gpo_actual->name . ' -> ' . $solicitud_t->gpo_nuevo->name;
            $sol_gpo_detail = $sol_gpo_detail . ')';

            $nombre_resp = $fecha_resp = $comment_resp = $cta_resp = $color_resp = '--';
            $user_resp = $fcaptura_resp = $rechazo_resp = $date_diff_lote_resp = '--';
            if( isset($solicitud_t->resultado_solicitud) ) {
                $nombre_resp    = $solicitud_t->resultado_solicitud->name;
                $fecha_resp     = Helpers::formatdatetime2($solicitud_t->resultado_solicitud->resultado_lote->attended_at);
                $fcaptura_resp  = Helpers::formatdatetime2($solicitud_t->resultado_solicitud->resultado_lote->created_at);
                $user_resp      = $solicitud_t->resultado_solicitud->user->name;
                $comment_resp   = $solicitud_t->resultado_solicitud->comment;
                $date_diff_lote_resp =
                    Helpers::formatdif_dias2(
                        date_create($solicitud_t->lote->fecha_oficio_lote),
                        date_create($solicitud_t->resultado_solicitud->resultado_lote->attended_at) );
                $color_resp     = 'text-danger';
                $rechazo_resp   = 'No procede';

                if( isset($solicitud_t->resultado_solicitud->rechazo_mainframe) ) {
                    if( ($solicitud_t->resultado_solicitud->status == 1) ) {
                        $color_resp = 'text-warning';
                        $rechazo_resp = 'Pendiente';
                    }
                    $cta_resp = $nombre_resp = '--';
                    $rechazo_resp = $rechazo_resp . $solicitud_t->resultado_solicitud->rechazo_mainframe->name;
                }
                else {
                    $cta_resp       = $solicitud_t->resultado_solicitud->cuenta;
                    $color_resp     = 'text-success';
                    $rechazo_resp   = 'Atendida';
                }
            }
            //dd($solicitud_t);
            return view( 'ctas.solicitudes.timeline',
                compact(
                    'solicitud_t',

                    'sol_gpo_detail',
                    'nombre_resp',
                    'fecha_resp',
                    'comment_resp',
                    'cta_resp',
                    'color_resp',
                    'user_resp',
                    'fcaptura_resp',
                    'rechazo_resp',
                    'date_diff_lote_resp'
                    ) );
        }
        else {
            Log::info('Sin permiso-Consultar timeline solicitudes. Usuario:' . Auth::user()->name . '|Del:' . $del);

            abort(403,'No tiene permitido ver este timeline');
        }
    }

}
