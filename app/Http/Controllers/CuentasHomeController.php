<?php

namespace App\Http\Controllers;

use App\Solicitud;
use App\Subdelegacion;
use App\Inventory_cta;
use App\Inventory;
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
/*         $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_job_id = Auth::user()->job_id;
        $user_job_name = Auth::user()->job->name;
        $user_del_id = Auth::user()->delegacion_id;
        $user_del_name = Auth::user()->delegacion->name; */

        $texto_log = 'User_id:' . $user->id . '|User:' . $user->name . '|Del:' . $user->delegacion_id . '|Job:' . $user->job->id;

        Log::info('Visitando Ctas-Home ' . $texto_log);

        if ( $user->hasRole('capturista_dspa') || $user->hasRole('capturista_cceyvd') ) {
            $primer_renglon = $user->delegacion->name . ' - ' . $user->job->name;
            return view('ctas.home_ctas', [
                'primer_renglon'    => $primer_renglon,
            ]);
        }
        elseif ( $user->hasRole('capturista_delegacional') )
        // elseif ( $user->hasRole('capturista_dspa') )
        {

            $primer_renglon = 'Delegación ' . str_pad($user->delegacion->id, 2, '0', STR_PAD_LEFT) . ' ' . $user->delegacion->name;
            $subdelegaciones = Subdelegacion::where('delegacion_id', $user->delegacion->id)
                                            ->where('status', '<>', 0)
                                            ->orderBy('num_sub', 'asc')->get();

            $inventory_id = env('INVENTORY_ID');
            $ca_group_01 = env('CA_GROUP_01');
            $ca_group_01_eq = env('CA_GROUP_01_EQ');

            $total_ctas = DB::table('detalle_ctas AS D')
                ->select('D.cuenta', 'D.name', 'D.install_data',
                    DB::raw("CASE WHEN G1.name = '$ca_group_01_eq' THEN '$ca_group_01' ELSE G1.name END AS gpo_name"),
                    'W.name AS work_area_name',
                    DB::raw("EXISTS(SELECT 1 FROM detalle_ctas WHERE ciz_id = 1 AND inventory_id = $inventory_id AND cuenta = D.cuenta) AS CIZ1"),
                    DB::raw("EXISTS(SELECT 1 FROM detalle_ctas WHERE ciz_id = 2 AND inventory_id = $inventory_id AND cuenta = D.cuenta) AS CIZ2"),
                    DB::raw("EXISTS(SELECT 1 FROM detalle_ctas WHERE ciz_id = 3 AND inventory_id = $inventory_id AND cuenta = D.cuenta) AS CIZ3") )
                ->join('groups AS G1', 'D.gpo_owner_id', '=', 'G1.id')
                ->join('work_areas AS W', 'D.work_area_id', '=', 'W.id')
                ->where( 'D.inventory_id', $inventory_id )
                ->where('D.delegacion_id', $user->delegacion->id)
                ->distinct()->get()->count();

            $cut_off_date = Inventory::find( env('INVENTORY_ID') )->cut_off_date;

            $total_ctas_genericas = Inventory_cta::with(['gpo_owner', 'work_area', 'solicitud_with_baja'])
                ->where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('work_area_id', 2)
                ->get()->count();
            
            $registros_en_baja = Inventory_cta::with(['gpo_owner', 'work_area', 'solicitud_with_baja'])
                ->where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('work_area_id', 2)
                ->whereHas('solicitud_with_baja')->get()->count();

            $total_ctas_genericas = $total_ctas_genericas - $registros_en_baja;
            

            $total_ctas_clas =  Inventory_cta::with(['gpo_owner', 'work_area', 'solicitud_with_baja'])
                ->where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('work_area_id', 4)
                ->get()->count();

            $registros_en_baja = Inventory_cta::with(['gpo_owner', 'work_area', 'solicitud_with_baja'])
                ->where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('work_area_id', 4)
                ->whereHas('solicitud_with_baja')->get()->count();

            $total_ctas_clas = $total_ctas_clas - $registros_en_baja;


            $total_ctas_fisca =  Inventory_cta::with(['gpo_owner', 'work_area', 'solicitud_with_baja'])
                ->where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('work_area_id', 46)
                ->get()->count();

            $registros_en_baja = Inventory_cta::with(['gpo_owner', 'work_area', 'solicitud_with_baja'])
                ->where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('work_area_id', 46)
                ->whereHas('solicitud_with_baja')->get()->count();

            $total_ctas_fisca = $total_ctas_fisca - $registros_en_baja;
            

            $total_ctas_svc =  Inventory_cta::with(['gpo_owner', 'work_area', 'solicitud_with_baja'])
                ->where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('work_area_id', 6)
                ->get()->count();

            $registros_en_baja = Inventory_cta::with(['gpo_owner', 'work_area', 'solicitud_with_baja'])
                ->where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('work_area_id', 6)
                ->whereHas('solicitud_with_baja')->get()->count();

            $total_ctas_svc = $total_ctas_svc - $registros_en_baja;
            
            
            $total_ctas_cobranza =  Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('work_area_id', 50)
                ->get()->count();

            $registros_en_baja = Inventory_cta::with('solicitud_with_baja')
                ->where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('work_area_id', 50)
                ->whereHas('solicitud_with_baja')->get()->count();

            $total_ctas_cobranza = $total_ctas_cobranza - $registros_en_baja;
            

            $total_ctas_SSJSAV =  Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('gpo_owner_id', 1)
                ->get()->count();

            $registros_en_baja = Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('gpo_owner_id', 1)
                ->whereHas('solicitud_with_baja')->get()->count();

            $total_ctas_SSJSAV = $total_ctas_SSJSAV - $registros_en_baja;


            $total_ctas_SSJDAV =  Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('gpo_owner_id', 2)
                ->get()->count();

            $registros_en_baja = Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('gpo_owner_id', 2)
                ->whereHas('solicitud_with_baja')->get()->count();

            $total_ctas_SSJDAV = $total_ctas_SSJDAV - $registros_en_baja;


            $total_ctas_SSJOFA =  Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('gpo_owner_id', 3)
                ->get()->count();

            $registros_en_baja = Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('gpo_owner_id', 3)
                ->whereHas('solicitud_with_baja')->get()->count();

            $total_ctas_SSJOFA = $total_ctas_SSJOFA - $registros_en_baja;


            $total_ctas_SSCONS =  Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where( 'inventory_id', $inventory_id)
                        ->where( function ($list_where) 
                                {
                                    $list_where
                                    ->where('gpo_owner_id', 7)
                                    ->orWhere('gpo_owner_id', 85);
                                })->get()->count();

            $registros_en_baja = Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where( function ($list_where) 
                                {
                                    $list_where
                                    ->where('gpo_owner_id', 7)
                                    ->orWhere('gpo_owner_id', 85);
                                })
                ->whereHas('solicitud_with_baja')->get()->count();

            $total_ctas_SSCONS = $total_ctas_SSCONS - $registros_en_baja;





            $total_inventario_SSADIF =  Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('gpo_owner_id', 12)
                ->get()->count();

            $registros_nuevos_SSADIF = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 1 )
                ->where('solicitudes.gpo_nuevo_id', 12)
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $registros_cambio_nuevos_SSADIF = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 3 )
                ->where('solicitudes.gpo_nuevo_id', 12)
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $registros_en_baja_SSADIF = Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('gpo_owner_id', 12)
                ->whereHas('solicitud_with_baja')->get()->count();

            $registros_cambio_anteriores_SSADIF = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 3 )
                ->where('solicitudes.gpo_actual_id', 12)
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $total_ctas_SSADIF = $total_inventario_SSADIF + $registros_nuevos_SSADIF + $registros_cambio_nuevos_SSADIF 
                - $registros_en_baja_SSADIF - $registros_cambio_anteriores_SSADIF;


            $total_ctas_SSOPER =  Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('gpo_owner_id', 6)
                ->get()->count();

            $registros_en_baja = Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('gpo_owner_id', 6)
                ->whereHas('solicitud_with_baja')->get()->count();

            $total_ctas_SSOPER = $total_ctas_SSOPER - $registros_en_baja;

            return view('ctas.home_ctas', 
                compact( 'primer_renglon', 
                    'subdelegaciones',
                    'total_ctas',
                    'total_ctas_genericas',
                    'total_ctas_clas',
                    'total_ctas_fisca',
                'total_ctas_svc',
                'total_ctas_cobranza',
                'total_ctas_SSJSAV',
                'total_ctas_SSJDAV',
                'total_ctas_SSJOFA',
                'total_ctas_SSCONS',

                'total_ctas_SSADIF',
                'total_inventario_SSADIF', 
                'registros_nuevos_SSADIF', 
                'registros_cambio_nuevos_SSADIF', 
                'registros_en_baja_SSADIF', 
                'registros_cambio_anteriores_SSADIF',

                'total_ctas_SSOPER') );
        }
        else return "No estas autorizado a ver esta página";
    }

    public function show_resume() {

        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user->delegacion->id = Auth::user()->delegacion_id;
        $texto_log = '|User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user->delegacion->id;

        if (Gate::allows('ver_resumen_admin_ctas')) {
            Log::info('Ver Resumen' . $texto_log);

            if (Auth::user()->hasRole('capturista_dspa'))
            {
                $primer_renglon = 'Nivel Central - DSPA';
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
                                ->select('lotes.num_lote', 'lotes.num_oficio_ca', 'lotes.fecha_oficio_lote', 'lotes.ticket_msi', 'lotes.comment', 'resultado_lotes.attended_at', DB::raw('COUNT(solicitudes.id) as total_solicitudes'))
                                ->groupBy('lotes.num_lote', 'lotes.num_oficio_ca', 'lotes.fecha_oficio_lote', 'lotes.ticket_msi', 'lotes.comment', 'resultado_lotes.attended_at')
                                ->orderBy('lotes.id', 'desc')->limit(20)->get();

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

    public function show_admin_tabla() {
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user->delegacion->id = Auth::user()->delegacion_id;
        $texto_log = '|User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user->delegacion->id;

        if (Gate::allows('genera_tabla_oficio')) {

            Log::info('Genera Tabla' . $texto_log);

            $tabla_movimientos = DB::table('solicitudes')
                ->leftjoin('valijas', 'solicitudes.valija_id', '=', 'valijas.id')
                ->join('movimientos', 'solicitudes.movimiento_id', '=', 'movimientos.id')
                ->leftjoin('groups as gpo_a', 'solicitudes.gpo_actual_id', '=', 'gpo_a.id')
                ->leftjoin('groups as gpo_n', 'solicitudes.gpo_nuevo_id', '=', 'gpo_n.id')
                ->select('valijas.id as val_id', 'valijas.num_oficio_ca',
                    'solicitudes.id as sol_id', 'solicitudes.primer_apellido', 'solicitudes.segundo_apellido', 'solicitudes.nombre',
                    'solicitudes.cuenta', 'solicitudes.matricula', 'solicitudes.curp', 'solicitudes.archivo',
                    'gpo_a.name as gpo_a_name', 'gpo_n.name as gpo_n_name', 'movimientos.id as mov_id', 'movimientos.name as mov_name')
                ->where('solicitudes.rechazo_id', NULL)
                ->where('solicitudes.lote_id', NULL)
                ->orderBy('solicitudes.movimiento_id')
                ->orderBy('solicitudes.cuenta')
                ->orderBy('valijas.num_oficio_ca')
                ->get();

            $first_query =
                DB::table('solicitudes')
                    ->leftjoin('valijas', 'solicitudes.valija_id', '=', 'valijas.id')
                    ->select('valijas.id',
                        'valijas.num_oficio_del',
                        'valijas.num_oficio_ca',
                        'valijas.delegacion_id',
                        'solicitudes.delegacion_id as sol_del_id',
                        DB::raw('count(solicitudes.id) as soli_count') )
                    ->where('solicitudes.lote_id', NULL)
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
                    ->where('solicitudes.lote_id', NULL)
                    ->where('valijas.id',NULL)
                    ->groupBy('valijas.id',
                        'valijas.num_oficio_del',
                        'valijas.num_oficio_ca',
                        'valijas.delegacion_id',
                        'solicitudes.delegacion_id')
                    ->orderBy('valijas.delegacion_id')
                    ->union($first_query)
                    ->get();

            $listado_mov_rechazados =
                Solicitud::with([
                    'valija',
                    'delegacion',
                    'subdelegacion',
                    'movimiento',
                    'rechazo',
                    'gpo_actual',
                    'gpo_nuevo',
                    'lote'])
                ->where('lote_id', NULL)
                ->where('rechazo_id', '<>', NULL)
                ->orderBy('solicitudes.movimiento_id')
                ->orderBy('solicitudes.cuenta')
                ->get();

            return view(
                'ctas.admin.show_tabla', [
                'tabla_movimientos'      => $tabla_movimientos,
                'listado_valijas'        => $listado_valijas,
                'listado_mov_rechazados' => $listado_mov_rechazados,
            ]);
        }
        else {
            Log::info('Sin permiso-Generar Tabla' . $texto_log);

            abort(403,'No tiene permitido ver esta tabla');
        }
    }
}
