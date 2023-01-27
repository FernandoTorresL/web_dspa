<?php

namespace App\Http\Controllers;

use App\Solicitud;
// use App\Subdelegacion;
// use App\Inventory_cta;
// use App\Inventory;
use App\Lote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class CuentasHomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        $user = Auth::user();

        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_job_id = Auth::user()->job_id;
        $user_del_id = Auth::user()->delegacion_id;
        $user_del_name = Auth::user()->delegacion->name;

        $texto_log = 'User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id . '|Job:' . $user_job_id;

        Log::info('Visitando Ctas-Home ' . $texto_log);

        $primer_renglon = $user_del_name;

        if ( $user->hasRole('capturista_dspa') || $user->hasRole('capturista_cceyvd') || $user->hasRole('autorizador_cceyvd')) {

            $primer_renglon .= ' - ' . $user->job->name;

            return view('ctas.home_ctas', compact('primer_renglon', 'user_del_id') );
        }
        elseif ( $user->hasRole('capturista_delegacional') )
            {
            $primer_renglon = env('OOAD') . ' ' . $primer_renglon . ' (' . str_pad($user_del_id, 2, '0', STR_PAD_LEFT) . ')';

            return view('ctas.home_ctas', compact('primer_renglon', 'user_del_id') );
        }
        else return "No estas autorizado a ver esta página";
    }

    public function show_resume() {

        $user = Auth::user();

        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_delegacion_id = Auth::user()->delegacion_id;

        $texto_log = '|User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user->delegacion->id;

        if (Gate::allows('ver_resumen_admin_ctas')) {
            Log::info('Ver Resumen' . $texto_log);

            if (Auth::user()->hasRole('capturista_dspa'))
            {
                $primer_renglon = 'Nivel Central - ' . env('DSPA_NAME');
            }

            $solicitudes_sin_lote = DB::table('solicitudes')
                ->leftjoin('valijas', 'solicitudes.valija_id', '=', 'valijas.id')
                ->join('movimientos', 'solicitudes.movimiento_id', '=', 'movimientos.id')
                ->select('valijas.origen_id', 'movimientos.name', DB::raw('COUNT(solicitudes.id) as total_solicitudes'))
                ->where('solicitudes.lote_id','=', NULL)
                ->groupBy('valijas.origen_id', 'movimientos.name')
                ->orderBy('origen_id')->orderBy('name')
                ->get();


            $listado_lotes = DB::table('lotes')
                ->leftjoin('resultado_lotes', 'lotes.id', '=', 'resultado_lotes.lote_id')
                ->leftjoin('solicitudes', 'lotes.id', '=', 'solicitudes.lote_id')
                ->select('lotes.id', 'lotes.num_lote', 'lotes.num_oficio_ca', 'lotes.fecha_oficio_lote', 'lotes.ticket_msi', 'lotes.comment', 'resultado_lotes.attended_at', DB::raw('COUNT(solicitudes.id) as total_solicitudes'))
                ->groupBy('lotes.id', 'lotes.num_lote', 'lotes.num_oficio_ca', 'lotes.fecha_oficio_lote', 'lotes.ticket_msi', 'lotes.comment', 'resultado_lotes.attended_at')
                ->orderBy('lotes.id', 'desc')->limit(40)->get();

            $solicitudes_sin_lote2 = Solicitud::select('id', 'lote_id', 'valija_id', 'archivo', 'created_at', 'updated_at', 'delegacion_id', 'subdelegacion_id',
                'cuenta', 'nombre', 'primer_apellido', 'segundo_apellido', 'movimiento_id', 'rechazo_id', 'final_remark', 'comment', 'user_id', 'gpo_actual_id', 'gpo_nuevo_id', 'matricula', 'curp')
                ->with('user', 'valija', 'delegacion', 'subdelegacion', 'movimiento', 'rechazo', 'gpo_actual', 'gpo_nuevo')
                ->orderBy('id', 'desc')
                ->limit(250)
                ->get();

            return view(
                'ctas.admin.show_resume', [
                'solicitudes_sin_lote' => $solicitudes_sin_lote,
                'solicitudes_sin_lote2' => $solicitudes_sin_lote2,
                'listado_lotes'      => $listado_lotes,
            ]);
        }
        else {
            Log::info('Sin permiso-Ver Resumen-Admin' . $texto_log);

            abort(403,'No tiene permitido ver esta tabla resumen');
        }
    }

    public function show_admin_tabla($lote_id = NULL) {

        $user = Auth::user();

        $texto_log = 'User_id:' . $user->id . '|User:' . $user->name . '|Del:' . $user->delegacion_id . '|Job:' . $user->job->id;

        if (Gate::allows('genera_tabla_oficio')) {

            Log::info('Genera Tabla' . $texto_log);

            if (!isset($lote_id))
                $id_lote = NULL;
            else
                $id_lote = $lote_id;

            $solicitud_id = NULL;

            $info_lote = Lote::find($id_lote);

            $lista_de_lotes = Lote::orderBy('id', 'desc')->get();

            $solicitudes_preautorizadas = Solicitud::with( ['valija_oficio', 'gpo_actual', 'gpo_nuevo', 'status_sol'] )
                ->where('solicitudes.lote_id', NULL)
                ->where('solicitudes.status_sol_id', 5)
                ->orderBy('solicitudes.movimiento_id')
                ->orderBy('solicitudes.cuenta');

            $solicitudes_sin_preautorizacion = Solicitud::with( ['valija_oficio', 'gpo_actual', 'gpo_nuevo', 'status_sol'] )
                ->where('solicitudes.lote_id', NULL)
                ->where('solicitudes.status_sol_id', '<>', 5)
                ->orderBy('solicitudes.movimiento_id')
                ->orderBy('solicitudes.cuenta');

            $solicitudes_con_respuesta_mainframe_ok = Solicitud::with( ['valija_oficio', 'gpo_actual', 'gpo_nuevo', 'resultado_solicitud'] )
                ->where('solicitudes.lote_id', $id_lote)
                ->whereHas('resultado_solicitud.resultado_lote', function ( $list_where ) {
                    $list_where
                        ->whereNull('rechazo_mainframe_id'); }
                )
                ->orderBy('solicitudes.movimiento_id')
                ->orderBy('solicitudes.cuenta');

            $solicitudes_con_respuesta_mainframe_error = Solicitud::with( ['valija_oficio', 'gpo_actual', 'gpo_nuevo', 'resultado_solicitud'] )
                ->where('solicitudes.lote_id', $id_lote)
                ->whereHas('resultado_solicitud.resultado_lote', function ( $list_where ) {
                    $list_where
                        ->whereNotNull('rechazo_mainframe_id'); }
                )
                ->orderBy('solicitudes.movimiento_id')
                ->orderBy('solicitudes.cuenta');

            $solicitudes_sin_respuesta_mainframe = Solicitud::whereDoesntHave('resultado_solicitud')
                ->where('solicitudes.lote_id', $id_lote)
                ->where('solicitudes.rechazo_id', NULL)
                ->orderBy('solicitudes.movimiento_id')
                ->orderBy('solicitudes.cuenta');

            $solicitudes_rechazadas_con_lote = Solicitud::with( ['valija_oficio', 'gpo_actual', 'gpo_nuevo', 'status_sol'] )
                ->where('solicitudes.lote_id', $id_lote)
                ->whereIn('solicitudes.status_sol_id', [3, 9, 10])
                ->orderBy('solicitudes.movimiento_id')
                ->orderBy('solicitudes.cuenta');

            $first_query =
                DB::table('solicitudes')
                    ->leftjoin('valijas', 'solicitudes.valija_id', '=', 'valijas.id')
                    ->select('valijas.id',
                        'valijas.num_oficio_del',
                        'valijas.num_oficio_ca',
                        'valijas.delegacion_id',
                        'solicitudes.delegacion_id as sol_del_id',
                        DB::raw('count(solicitudes.id) as soli_count') )
                    ->where('solicitudes.lote_id', $id_lote)
                    ->where('valijas.id', '<>', NULL)
                    ->groupBy('valijas.id',
                        'valijas.num_oficio_del',
                        'valijas.num_oficio_ca',
                        'valijas.delegacion_id',
                        'solicitudes.delegacion_id')
                    ->orderBy('valijas.num_oficio_ca');

            $listado_valijas =
                DB::table('solicitudes')
                    ->leftjoin('valijas', 'solicitudes.valija_id', '=', 'valijas.id')
                    ->select('valijas.id',
                        'valijas.num_oficio_del',
                        'valijas.num_oficio_ca',
                        'valijas.delegacion_id',
                        'solicitudes.delegacion_id as sol_del_id',
                        DB::raw('count(solicitudes.id) as soli_count') )
                    ->where('solicitudes.lote_id', $id_lote)
                    ->where('valijas.id',NULL)
                    ->groupBy('valijas.id',
                        'valijas.num_oficio_del',
                        'valijas.num_oficio_ca',
                        'valijas.delegacion_id',
                        'solicitudes.delegacion_id')
                    ->orderBy('valijas.delegacion_id');

            $solicitudes_con_respuesta_mainframe_ok = $solicitudes_con_respuesta_mainframe_ok->get();
            $solicitudes_con_respuesta_mainframe_error = $solicitudes_con_respuesta_mainframe_error->get();
            $solicitudes_sin_respuesta_mainframe = $solicitudes_sin_respuesta_mainframe->get();

            if ( isset( $solicitud_id ) ) {
                $solicitudes_preautorizadas = $solicitudes_preautorizadas->where('solicitudes.id', '<=', $solicitud_id)->get();
                $solicitudes_sin_preautorizacion = $solicitudes_sin_preautorizacion->where('solicitudes.id', '<=', $solicitud_id)->get();
                $listado_valijas = $listado_valijas->where('solicitudes.id', '<=', $solicitud_id)->union($first_query)->get();
                $solicitudes_rechazadas_con_lote = $solicitudes_rechazadas_con_lote->where('solicitudes.id', '<=', $solicitud_id)->get();
            }
            else {
                $solicitudes_preautorizadas = $solicitudes_preautorizadas->get();
                $solicitudes_sin_preautorizacion = $solicitudes_sin_preautorizacion->get();
                $listado_valijas = $listado_valijas->union($first_query)->get();
                $solicitudes_rechazadas_con_lote = $solicitudes_rechazadas_con_lote->get();
            }

            return view(
                'ctas.admin.show_tabla', compact (
                    'solicitudes_preautorizadas',
                    'solicitudes_sin_preautorizacion',
                    'solicitudes_con_respuesta_mainframe_ok',
                    'solicitudes_con_respuesta_mainframe_error',
                    'solicitudes_sin_respuesta_mainframe',
                    'solicitudes_rechazadas_con_lote',

                    'listado_valijas',
                    'lista_de_lotes',

                    'info_lote',
                    'solicitud_id'
                )
            );
        }
        else {
            Log::info('Sin permiso-Generar Tabla' . $texto_log);

            abort(403,'No tiene permitido ver esta tabla');
        }
    }
}
