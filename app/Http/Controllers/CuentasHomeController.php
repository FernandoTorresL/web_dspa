<?php

namespace App\Http\Controllers;

use App\Solicitud;
use App\Subdelegacion;
use App\Inventory_cta;
use App\Inventory;
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

        $texto_log = 'User_id:' . $user->id . '|User:' . $user->name . '|Del:' . $user->delegacion_id . '|Job:' . $user->job->id;

        Log::info('Visitando Ctas-Home ' . $texto_log);

        $inventory_id = env('INVENTORY_ID');
        $cut_off_date = Inventory::find( env('INVENTORY_ID') )->cut_off_date;

        $primer_renglon = $user->delegacion->name;

        if ( $user->hasRole('capturista_dspa') || $user->hasRole('capturista_cceyvd') || $user->hasRole('autorizador_cceyvd')) {

            $primer_renglon .= ' - ' . $user->job->name;

            $total_inv_Ctas =  Inventory_cta::where('inventory_id', $inventory_id)
                ->get()->count();

            $nuevos_Ctas = Solicitud::where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 1 )
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $bajas_Ctas = Inventory_cta::where('inventory_id', $inventory_id)
                ->whereHas('solicitud_with_baja')->get()->count();

            $total_ctas_Ctas = $total_inv_Ctas + $nuevos_Ctas - $bajas_Ctas;

            return view('ctas.home_ctas', compact( 
                'primer_renglon', 

                'total_ctas_Ctas',
                'total_inv_Ctas', 
                'nuevos_Ctas', 
                'bajas_Ctas' ) );
        }
        elseif ( $user->hasRole('capturista_delegacional') )
            {
            $primer_renglon = 'Delegación ' . str_pad($user->delegacion->id, 2, '0', STR_PAD_LEFT) . ' ' . $primer_renglon;
 
            $subdelegaciones = Subdelegacion::where('delegacion_id', $user->delegacion->id)
                                            ->where('status', '<>', 0)
                                            ->orderBy('num_sub', 'asc')->get();

            $total_inv_Ctas =  Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->get()->count();

            $nuevos_Ctas = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 1 )
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $bajas_Ctas = Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->whereHas('solicitud_with_baja')->get()->count();

            $total_ctas_Ctas = $total_inv_Ctas + $nuevos_Ctas - $bajas_Ctas;

            //General count on inventory by work_area
            $array_inv_xwork_area =  Inventory_cta::select( DB::raw('count(*) as work_count, work_area_id') )
                ->where('inventory_id', $inventory_id)->where('delegacion_id', $user->delegacion->id)
                ->groupby('work_area_id')->pluck('work_count', 'work_area_id');

            //General count on inventory by group
            $array_inv_xgpo_owner =  Inventory_cta::select( DB::raw( 'gpo_owner_id, count(*) as group_count') )
                ->where('inventory_id', $inventory_id)->where('delegacion_id', $user->delegacion->id)
                ->groupby('gpo_owner_id')->pluck('group_count', 'gpo_owner_id');

            //General count of Solicitudes-ALTAS by group
            $array_sol_altas_xgpo = Solicitud::select( DB::raw( 'gpo_nuevo_id, count(*) as group_count') )
                ->where('delegacion_id', $user->delegacion->id)->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 1 )
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )
                ->groupby('gpo_nuevo_id')->pluck('group_count', 'gpo_nuevo_id');

            //General count of Solicitudes-CAMBIOS(adding some group) by group
            $array_sol_cambios_xgpo_nuevo = Solicitud::select( DB::raw( 'gpo_nuevo_id, count(*) as group_count') )
                ->where('delegacion_id', $user->delegacion->id)->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 3 )
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )
                ->groupby('gpo_nuevo_id')->pluck('group_count', 'gpo_nuevo_id');

            //General count of Inventory with Solicitudes-BAJAS by group
            $array_inv_wbajas_xgpo_owner = Inventory_cta::select( DB::raw( 'gpo_owner_id, count(*) as group_count') )
                ->where('inventory_id', $inventory_id)->where('delegacion_id', $user->delegacion->id)
                ->whereHas('solicitud_with_baja')
                ->groupby('gpo_owner_id')->pluck('group_count', 'gpo_owner_id');

            //General count of Solicitudes-CAMBIOS(leaving some group) by group
            $array_sol_cambios_xgpo_actual = Solicitud::select( DB::raw( 'gpo_actual_id, count(*) as group_count') )
                ->where('delegacion_id', $user->delegacion->id)->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 3 )
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )
                ->groupby('gpo_actual_id')->pluck('group_count', 'gpo_actual_id');

            //General count on inventory with Solicitudes-BAJAS by work
            $array_inv_wbajas_xwork_area =  Inventory_cta::select( DB::raw('count(*) as work_count, work_area_id') )
                ->where('inventory_id', $inventory_id)->where('delegacion_id', $user->delegacion->id)
                ->whereHas('solicitud_with_baja')
                ->groupby('work_area_id')->pluck('work_count', 'work_area_id');

            //Genéricas count
            $total_inv_Genericas = $bajas_Genericas = 0;
            $id_work_area = 2;

            if( isset( $array_inv_xwork_area[$id_work_area] ) ) 
                $total_inv_Genericas = $array_inv_xwork_area[$id_work_area];
            if( isset( $array_inv_wbajas_xwork_area[$id_work_area] ) ) 
                $bajas_Genericas = $array_inv_wbajas_xwork_area[$id_work_area];

            $total_ctas_Genericas = $total_inv_Genericas - $bajas_Genericas;

            //Clas count
            $total_inv_Clas = $nuevos_Clas = $cambio_nuevos_Clas = $bajas_Clas = 0;
            $id_work_area = 4;

            if( isset( $array_inv_xwork_area[$id_work_area] ) ) 
                $total_inv_Clas = $array_inv_xwork_area[$id_work_area];
    
            $id_gpo = 4;
            if( isset( $array_sol_altas_xgpo[$id_gpo] ) ) 
                $nuevos_Clas += $array_sol_altas_xgpo[$id_gpo];
            if( isset( $array_sol_cambios_xgpo_nuevo[$id_gpo] ) ) 
                $cambio_nuevos_Clas += $array_sol_cambios_xgpo_nuevo[$id_gpo];

            $id_gpo = 5;
            if( isset( $array_sol_altas_xgpo[$id_gpo] ) ) 
                $nuevos_Clas += $array_sol_altas_xgpo[$id_gpo];
            if( isset( $array_sol_cambios_xgpo_nuevo[$id_gpo] ) ) 
                $cambio_nuevos_Clas += $array_sol_cambios_xgpo_nuevo[$id_gpo];

            $id_gpo = 8;
            if( isset( $array_sol_altas_xgpo[$id_gpo] ) ) 
                $nuevos_Clas += $array_sol_altas_xgpo[$id_gpo];
            if( isset( $array_sol_cambios_xgpo_nuevo[$id_gpo] ) ) 
                $cambio_nuevos_Clas += $array_sol_cambios_xgpo_nuevo[$id_gpo];

            $id_gpo = 9;
            if( isset( $array_sol_altas_xgpo[$id_gpo] ) ) 
                $nuevos_Clas += $array_sol_altas_xgpo[$id_gpo];
            if( isset( $array_sol_cambios_xgpo_nuevo[$id_gpo] ) ) 
                $cambio_nuevos_Clas += $array_sol_cambios_xgpo_nuevo[$id_gpo];

            $id_gpo = 10;
            if( isset( $array_sol_altas_xgpo[$id_gpo] ) ) 
                $nuevos_Clas += $array_sol_altas_xgpo[$id_gpo];
            if( isset( $array_sol_cambios_xgpo_nuevo[$id_gpo] ) ) 
                $cambio_nuevos_Clas += $array_sol_cambios_xgpo_nuevo[$id_gpo];

            $id_gpo = 11;
            if( isset( $array_sol_altas_xgpo[$id_gpo] ) ) 
                $nuevos_Clas += $array_sol_altas_xgpo[$id_gpo];
            if( isset( $array_sol_cambios_xgpo_nuevo[$id_gpo] ) ) 
                $cambio_nuevos_Clas += $array_sol_cambios_xgpo_nuevo[$id_gpo];

            $id_gpo = 24;
            if( isset( $array_sol_altas_xgpo[$id_gpo] ) ) 
                $nuevos_Clas += $array_sol_altas_xgpo[$id_gpo];
            if( isset( $array_sol_cambios_xgpo_nuevo[$id_gpo] ) ) 
                $cambio_nuevos_Clas += $array_sol_cambios_xgpo_nuevo[$id_gpo];

            if( isset( $array_inv_wbajas_xwork_area[$id_work_area] ) ) 
                $bajas_Clas = $array_inv_wbajas_xwork_area[$id_work_area];

            $cambio_anteriores_Clas = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )->where( 'solicitudes.movimiento_id', 3 )
                ->whereIn('solicitudes.gpo_actual_id', [4, 5, 8, 9, 10, 11, 24])
                ->whereNotIn('solicitudes.gpo_nuevo_id', [4, 5, 8, 9, 10, 11, 24])
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $total_ctas_Clas = $total_inv_Clas + $nuevos_Clas + $cambio_nuevos_Clas 
                - $bajas_Clas - $cambio_anteriores_Clas;

            //Fisca count
            $total_ctas_Fisca = 0;
            $id_work_area = 46;
            if( isset( $array_inv_xwork_area[$id_work_area] ) ) 
                $total_ctas_Fisca = $array_inv_xwork_area[$id_work_area];
                
            //SVC count
            $total_inv_SVC = $nuevos_SVC = $cambio_nuevos_SVC = $bajas_SVC = $cambio_anteriores_SVC = 0;
            $id_work_area = 6;
            if( isset( $array_inv_xwork_area[$id_work_area] ) ) 
                $total_inv_SVC = $array_inv_xwork_area[$id_work_area];

            $id_gpo = 18;
            if( isset( $array_sol_altas_xgpo[$id_gpo] ) ) 
                $nuevos_SVC = $array_sol_altas_xgpo[$id_gpo];

            if( isset( $array_sol_cambios_xgpo_nuevo[$id_gpo] ) ) 
                $cambio_nuevos_SVC = $array_sol_cambios_xgpo_nuevo[$id_gpo];

            if( isset( $array_inv_wbajas_xgpo_owner[$id_gpo] ) ) 
                $bajas_SVC = $array_inv_wbajas_xgpo_owner[$id_gpo];

            if( isset( $array_sol_cambios_xgpo_actual[$id_gpo] ) )
                $cambio_anteriores_SVC = $array_sol_cambios_xgpo_actual[$id_gpo];

            $total_ctas_SVC = $total_inv_SVC + $nuevos_SVC + $cambio_nuevos_SVC - $bajas_SVC - $cambio_anteriores_SVC;
            
            //Cobranza count
            $total_ctas_Cobranza = 0;
            $id_work_area = 50;
            if( isset( $array_inv_xwork_area[$id_work_area] ) ) 
                $total_ctas_Cobranza = $array_inv_xwork_area[$id_work_area];

            //SSJSAV count
            $total_inv_SSJSAV = $nuevos_SSJSAV = $cambio_nuevos_SSJSAV = $bajas_SSJSAV = $cambio_anteriores_SSJSAV = 0;
            $id_gpo = 1;
            if( isset( $array_inv_xgpo_owner[$id_gpo] ) ) 
                $total_inv_SSJSAV = $array_inv_xgpo_owner[$id_gpo];
            
            if( isset( $array_sol_altas_xgpo[$id_gpo] ) ) 
                $nuevos_SSJSAV = $array_sol_altas_xgpo[$id_gpo];

            if( isset( $array_sol_cambios_xgpo_nuevo[$id_gpo] ) ) 
                $cambio_nuevos_SSJSAV = $array_sol_cambios_xgpo_nuevo[$id_gpo];

            if( isset( $array_inv_wbajas_xgpo_owner[$id_gpo] ) ) 
                $bajas_SSJSAV = $array_inv_wbajas_xgpo_owner[$id_gpo];

            if( isset( $array_sol_cambios_xgpo_actual[$id_gpo] ) )
                $cambio_anteriores_SSJSAV = $array_sol_cambios_xgpo_actual[$id_gpo];

            $total_ctas_SSJSAV = $total_inv_SSJSAV + $nuevos_SSJSAV + $cambio_nuevos_SSJSAV - $bajas_SSJSAV - $cambio_anteriores_SSJSAV;

            //SSJDAV count
            $total_inv_SSJDAV = $nuevos_SSJDAV = $cambio_nuevos_SSJDAV = $bajas_SSJDAV = $cambio_anteriores_SSJDAV = 0;
            $id_gpo = 2;
            if( isset( $array_inv_xgpo_owner[$id_gpo] ) ) 
                $total_inv_SSJDAV = $array_inv_xgpo_owner[$id_gpo];
            
            if( isset( $array_sol_altas_xgpo[$id_gpo] ) ) 
                $nuevos_SSJDAV = $array_sol_altas_xgpo[$id_gpo];
            
            if( isset( $array_sol_cambios_xgpo_nuevo[$id_gpo] ) ) 
                $cambio_nuevos_SSJDAV = $array_sol_cambios_xgpo_nuevo[$id_gpo];
            
            if( isset( $array_inv_wbajas_xgpo_owner[$id_gpo] ) ) 
                $bajas_SSJDAV = $array_inv_wbajas_xgpo_owner[$id_gpo];
            
            if( isset( $array_sol_cambios_xgpo_actual[$id_gpo] ) )
                $cambio_anteriores_SSJDAV = $array_sol_cambios_xgpo_actual[$id_gpo];
            
            $total_ctas_SSJDAV = $total_inv_SSJDAV + $nuevos_SSJDAV + $cambio_nuevos_SSJDAV - $bajas_SSJDAV - $cambio_anteriores_SSJDAV;

            //SSJOFA count
            $total_inv_SSJOFA = $nuevos_SSJOFA = $cambio_nuevos_SSJOFA = $bajas_SSJOFA = $cambio_anteriores_SSJOFA = 0;
            $id_gpo = 3;
            if( isset( $array_inv_xgpo_owner[$id_gpo] ) ) 
                $total_inv_SSJOFA = $array_inv_xgpo_owner[$id_gpo];

            if( isset( $array_sol_altas_xgpo[$id_gpo] ) ) 
                $nuevos_SSJOFA = $array_sol_altas_xgpo[$id_gpo];

            if( isset( $array_sol_cambios_xgpo_nuevo[$id_gpo] ) ) 
                $cambio_nuevos_SSJOFA = $array_sol_cambios_xgpo_nuevo[$id_gpo];

            if( isset( $array_inv_wbajas_xgpo_owner[$id_gpo] ) ) 
                $bajas_SSJOFA = $array_inv_wbajas_xgpo_owner[$id_gpo];

            if( isset( $array_sol_cambios_xgpo_actual[$id_gpo] ) )
                $cambio_anteriores_SSJOFA = $array_sol_cambios_xgpo_actual[$id_gpo];

            $total_ctas_SSJOFA = $total_inv_SSJOFA + $nuevos_SSJOFA + $cambio_nuevos_SSJOFA - $bajas_SSJOFA - $cambio_anteriores_SSJOFA;

            //SSCONS count
            $total_inv_SSCONS = $nuevos_SSCONS = $cambio_nuevos_SSCONS = $bajas_SSCONS = $cambio_anteriores_SSCONS = 0;
            $id_gpo = 7;
            if( isset( $array_inv_xgpo_owner[$id_gpo] ) ) 
                $total_inv_SSCONS = $array_inv_xgpo_owner[$id_gpo];
            
            if( isset( $array_sol_altas_xgpo[$id_gpo] ) ) 
                $nuevos_SSCONS = $array_sol_altas_xgpo[$id_gpo];
            
            if( isset( $array_sol_cambios_xgpo_nuevo[$id_gpo] ) ) 
                $cambio_nuevos_SSCONS = $array_sol_cambios_xgpo_nuevo[$id_gpo];
            
            if( isset( $array_inv_wbajas_xgpo_owner[$id_gpo] ) ) 
                $bajas_SSCONS = $array_inv_wbajas_xgpo_owner[$id_gpo];
            
            if( isset( $array_sol_cambios_xgpo_actual[$id_gpo] ) )
                $cambio_anteriores_SSCONS = $array_sol_cambios_xgpo_actual[$id_gpo];
            
            $total_ctas_SSCONS = $total_inv_SSCONS + $nuevos_SSCONS + $cambio_nuevos_SSCONS - $bajas_SSCONS - $cambio_anteriores_SSCONS;

            //SSADIF count
            $total_inv_SSADIF = $nuevos_SSADIF = $cambio_nuevos_SSADIF = $bajas_SSADIF = $cambio_anteriores_SSADIF = 0;
            $id_gpo = 12;
            if( isset( $array_inv_xgpo_owner[$id_gpo] ) ) 
                $total_inv_SSADIF = $array_inv_xgpo_owner[$id_gpo];
            
            if( isset( $array_sol_altas_xgpo[$id_gpo] ) ) 
                $nuevos_SSADIF = $array_sol_altas_xgpo[$id_gpo];
            
            if( isset( $array_sol_cambios_xgpo_nuevo[$id_gpo] ) ) 
                $cambio_nuevos_SSADIF = $array_sol_cambios_xgpo_nuevo[$id_gpo];
            
            if( isset( $array_inv_wbajas_xgpo_owner[$id_gpo] ) ) 
                $bajas_SSADIF = $array_inv_wbajas_xgpo_owner[$id_gpo];
            
            if( isset( $array_sol_cambios_xgpo_actual[$id_gpo] ) )
                $cambio_anteriores_SSADIF = $array_sol_cambios_xgpo_actual[$id_gpo];
            
            $total_ctas_SSADIF = $total_inv_SSADIF + $nuevos_SSADIF + $cambio_nuevos_SSADIF - $bajas_SSADIF - $cambio_anteriores_SSADIF;

            //SSOPER count
            $total_inv_SSOPER = $nuevos_SSOPER = $cambio_nuevos_SSOPER = $bajas_SSOPER = $cambio_anteriores_SSOPER = 0;
            $id_gpo = 6;
            if( isset( $array_inv_xgpo_owner[$id_gpo] ) ) 
                $total_inv_SSOPER = $array_inv_xgpo_owner[$id_gpo];

            $id_gpo = $id_gpo;
            if( isset( $array_sol_altas_xgpo[$id_gpo] ) ) 
                $nuevos_SSOPER = $array_sol_altas_xgpo[$id_gpo];

            if( isset( $array_sol_cambios_xgpo_nuevo[$id_gpo] ) ) 
                $cambio_nuevos_SSOPER = $array_sol_cambios_xgpo_nuevo[$id_gpo];

            if( isset( $array_inv_wbajas_xgpo_owner[$id_gpo] ) ) 
                $bajas_SSOPER = $array_inv_wbajas_xgpo_owner[$id_gpo];

            if( isset( $array_sol_cambios_xgpo_actual[$id_gpo] ) )
                $cambio_anteriores_SSOPER = $array_sol_cambios_xgpo_actual[$id_gpo];

            $total_ctas_SSOPER = $total_inv_SSOPER + $nuevos_SSOPER + $cambio_nuevos_SSOPER - $bajas_SSOPER - $cambio_anteriores_SSOPER;

            return view('ctas.home_ctas', compact( 
                'primer_renglon', 
                'subdelegaciones',

                'total_ctas_Ctas',
                'total_inv_Ctas', 
                'nuevos_Ctas', 
                'bajas_Ctas', 

                'total_ctas_Genericas',
                'total_inv_Genericas', 

                'bajas_Genericas', 
                
                'total_ctas_Clas',
                'total_inv_Clas', 
                'nuevos_Clas', 
                'cambio_nuevos_Clas', 
                'bajas_Clas', 
                'cambio_anteriores_Clas',
                
                'total_ctas_Fisca',
                
                'total_ctas_SVC',
                'total_inv_SVC', 
                'nuevos_SVC', 
                'cambio_nuevos_SVC', 
                'bajas_SVC', 
                'cambio_anteriores_SVC',

                'total_ctas_Cobranza',
                
                'total_ctas_SSJSAV',
                'total_inv_SSJSAV', 
                'nuevos_SSJSAV', 
                'cambio_nuevos_SSJSAV', 
                'bajas_SSJSAV', 
                'cambio_anteriores_SSJSAV',
                
                'total_ctas_SSJDAV',
                'total_inv_SSJDAV', 
                'nuevos_SSJDAV', 
                'cambio_nuevos_SSJDAV', 
                'bajas_SSJDAV', 
                'cambio_anteriores_SSJDAV',
                
                'total_ctas_SSJOFA',
                'total_inv_SSJOFA', 
                'nuevos_SSJOFA', 
                'cambio_nuevos_SSJOFA', 
                'bajas_SSJOFA', 
                'cambio_anteriores_SSJOFA',
                
                'total_ctas_SSCONS',
                'total_inv_SSCONS', 
                'nuevos_SSCONS', 
                'cambio_nuevos_SSCONS', 
                'bajas_SSCONS', 
                'cambio_anteriores_SSCONS',

                'total_ctas_SSADIF',
                'total_inv_SSADIF', 
                'nuevos_SSADIF', 
                'cambio_nuevos_SSADIF', 
                'bajas_SSADIF', 
                'cambio_anteriores_SSADIF',

                'total_ctas_SSOPER',
                'total_inv_SSOPER', 
                'nuevos_SSOPER', 
                'cambio_nuevos_SSOPER', 
                'bajas_SSOPER', 
                'cambio_anteriores_SSOPER') );
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

            if (isset($lote_id))
                $id_lote = $lote_id;

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
<<<<<<< HEAD
=======
                $solicitudes_rechazadas_con_lote = $solicitudes_rechazadas_con_lote->where('solicitudes.id', '<=', $solicitud_id)->get();
>>>>>>> master
            }
            else {
                $solicitudes_preautorizadas = $solicitudes_preautorizadas->get();
                $solicitudes_sin_preautorizacion = $solicitudes_sin_preautorizacion->get();
                $listado_valijas = $listado_valijas->union($first_query)->get();
<<<<<<< HEAD
=======
                $solicitudes_rechazadas_con_lote = $solicitudes_rechazadas_con_lote->get();
>>>>>>> master
            }

            return view(
                'ctas.admin.show_tabla', compact (
                    'solicitudes_preautorizadas',
                    'solicitudes_sin_preautorizacion',
                    'solicitudes_con_respuesta_mainframe_ok',
                    'solicitudes_con_respuesta_mainframe_error',
                    'solicitudes_sin_respuesta_mainframe',
<<<<<<< HEAD
=======
                    'solicitudes_rechazadas_con_lote',
>>>>>>> master

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
