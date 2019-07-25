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

        $texto_log = 'User_id:' . $user->id . '|User:' . $user->name . '|Del:' . $user->delegacion_id . '|Job:' . $user->job->id;

        Log::info('Visitando Ctas-Home ' . $texto_log);

        $inventory_id = env('INVENTORY_ID');
        $cut_off_date = Inventory::find( env('INVENTORY_ID') )->cut_off_date;

        if ( $user->hasRole('capturista_dspa') || $user->hasRole('capturista_cceyvd') ) {

            $primer_renglon = $user->delegacion->name . ' - ' . $user->job->name;

            $total_inventario_Ctas =  Inventory_cta::where('inventory_id', $inventory_id)
                ->get()->count();

            $registros_nuevos_Ctas = Solicitud::where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 1 )
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $registros_en_baja_Ctas = Inventory_cta::where('inventory_id', $inventory_id)
                ->whereHas('solicitud_with_baja')->get()->count();

            $total_ctas_Ctas = $total_inventario_Ctas + $registros_nuevos_Ctas - $registros_en_baja_Ctas;

            return view('ctas.home_ctas', compact( 
                'primer_renglon', 

                'total_ctas_Ctas',
                'total_inventario_Ctas', 
                'registros_nuevos_Ctas', 
                'registros_en_baja_Ctas', 
                ) );
        }
        elseif ( $user->hasRole('capturista_delegacional') )
            {

            $primer_renglon = 'Delegación ' . str_pad($user->delegacion->id, 2, '0', STR_PAD_LEFT) . ' - ' . $user->delegacion->name;
 
            $subdelegaciones = Subdelegacion::where('delegacion_id', $user->delegacion->id)
                                            ->where('status', '<>', 0)
                                            ->orderBy('num_sub', 'asc')->get();

            $ca_group_01 = env('CA_GROUP_01');
            $ca_group_01_eq = env('CA_GROUP_01_EQ');
 
            $total_inventario_Ctas =  Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->get()->count();

            $registros_nuevos_Ctas = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 1 )
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $registros_en_baja_Ctas = Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->whereHas('solicitud_with_baja')->get()->count();

            $total_ctas_Ctas = $total_inventario_Ctas + $registros_nuevos_Ctas - $registros_en_baja_Ctas;


            $array_total_inventario_work_area =  Inventory_cta::
                select( DB::raw('count(*) as work_count, work_area_id') )
                ->where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->groupby('work_area_id')
                ->pluck('work_count', 'work_area_id');

            $var_work_area_id = 2;
            if( isset( $array_total_inventario_work_area[$var_work_area_id] ) ) 
                $total_inventario_Genericas = $array_total_inventario_work_area[$var_work_area_id];
            else
                $total_inventario_Genericas = 0;

            $registros_nuevos_Genericas = 0;

            $registros_cambio_nuevos_Genericas = 0;

            $registros_en_baja_Genericas = Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('work_area_id', 2)
                ->whereHas('solicitud_with_baja')->get()->count();

            $registros_cambio_anteriores_Genericas = 0;

            $total_ctas_Genericas = $total_inventario_Genericas + $registros_nuevos_Genericas + $registros_cambio_nuevos_Genericas 
                - $registros_en_baja_Genericas - $registros_cambio_anteriores_Genericas;


            $var_work_area_id = 4;
                if( isset( $array_total_inventario_work_area[$var_work_area_id] ) ) 
                    $total_inventario_Clas = $array_total_inventario_work_area[$var_work_area_id];
                else
                    $total_inventario_Clas = 0;
    
            $registros_nuevos_Clas = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 1 )
                ->whereIn('solicitudes.gpo_nuevo_id', [4, 5, 8, 9, 10, 11, 24])
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $registros_cambio_nuevos_Clas = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 3 )
                ->whereIn('solicitudes.gpo_nuevo_id', [4, 5, 8, 9, 10, 11, 24])
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $registros_en_baja_Clas = Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->whereIn('gpo_owner_id', [4, 5, 8, 9, 10, 11, 24])
                ->whereHas('solicitud_with_baja')->get()->count();

            $registros_cambio_anteriores_Clas = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 3 )
                ->whereIn('solicitudes.gpo_actual_id', [4, 5, 8, 9, 10, 11, 24])
                ->whereNotIn('solicitudes.gpo_nuevo_id', [4, 5, 8, 9, 10, 11, 24])
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $total_ctas_Clas = $total_inventario_Clas + $registros_nuevos_Clas + $registros_cambio_nuevos_Clas 
                - $registros_en_baja_Clas - $registros_cambio_anteriores_Clas;


            $var_work_area_id = 46;
            if( isset( $array_total_inventario_work_area[$var_work_area_id] ) ) 
                $total_inventario_Fisca = $array_total_inventario_work_area[$var_work_area_id];
            else
                $total_inventario_Fisca = 0;

            $registros_nuevos_Fisca = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 1 )
                ->where('solicitudes.gpo_nuevo_id', 25)
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $registros_cambio_nuevos_Fisca = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 3 )
                ->where('solicitudes.gpo_nuevo_id', 25)
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $registros_en_baja_Fisca = Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('gpo_owner_id', 25)
                ->whereHas('solicitud_with_baja')->get()->count();

            $registros_cambio_anteriores_Fisca = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 3 )
                ->where('solicitudes.gpo_actual_id', 25)
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $total_ctas_Fisca = $total_inventario_Fisca + $registros_nuevos_Fisca + $registros_cambio_nuevos_Fisca 
                - $registros_en_baja_Fisca - $registros_cambio_anteriores_Fisca;
            
            $var_work_area_id = 6;
            if( isset( $array_total_inventario_work_area[$var_work_area_id] ) ) 
                $total_inventario_SVC = $array_total_inventario_work_area[$var_work_area_id];
            else
                $total_inventario_SVC = 0;

            $registros_nuevos_SVC = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 1 )
                ->where('solicitudes.gpo_nuevo_id', 18)
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $registros_cambio_nuevos_SVC = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 3 )
                ->where('solicitudes.gpo_nuevo_id', 18)
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $registros_en_baja_SVC = Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('gpo_owner_id', 18)
                ->whereHas('solicitud_with_baja')->get()->count();

            $registros_cambio_anteriores_SVC = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 3 )
                ->where('solicitudes.gpo_actual_id', 18)
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $total_ctas_SVC = $total_inventario_SVC + $registros_nuevos_SVC + $registros_cambio_nuevos_SVC 
                - $registros_en_baja_SVC - $registros_cambio_anteriores_SVC;
            
            $var_work_area_id = 50;
            if( isset( $array_total_inventario_work_area[$var_work_area_id] ) ) 
                $total_ctas_Cobranza = $array_total_inventario_work_area[$var_work_area_id];
            else
                $total_ctas_Cobranza = 0;

            $total_inventario_SSJSAV =  Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('gpo_owner_id', 1)
                ->get()->count();

            $registros_nuevos_SSJSAV = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 1 )
                ->where('solicitudes.gpo_nuevo_id', 1)
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $registros_cambio_nuevos_SSJSAV = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 3 )
                ->where('solicitudes.gpo_nuevo_id', 1)
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $registros_en_baja_SSJSAV = Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('gpo_owner_id', 1)
                ->whereHas('solicitud_with_baja')->get()->count();

            $registros_cambio_anteriores_SSJSAV = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 3 )
                ->where('solicitudes.gpo_actual_id', 1)
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $total_ctas_SSJSAV = $total_inventario_SSJSAV + $registros_nuevos_SSJSAV + $registros_cambio_nuevos_SSJSAV 
                - $registros_en_baja_SSJSAV - $registros_cambio_anteriores_SSJSAV;


            $total_inventario_SSJDAV =  Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('gpo_owner_id', 2)
                ->get()->count();

            $registros_nuevos_SSJDAV = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 1 )
                ->where('solicitudes.gpo_nuevo_id', 2)
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $registros_cambio_nuevos_SSJDAV = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 3 )
                ->where('solicitudes.gpo_nuevo_id', 2)
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $registros_en_baja_SSJDAV = Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('gpo_owner_id', 2)
                ->whereHas('solicitud_with_baja')->get()->count();

            $registros_cambio_anteriores_SSJDAV = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 3 )
                ->where('solicitudes.gpo_actual_id', 2)
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $total_ctas_SSJDAV = $total_inventario_SSJDAV + $registros_nuevos_SSJDAV + $registros_cambio_nuevos_SSJDAV 
                - $registros_en_baja_SSJDAV - $registros_cambio_anteriores_SSJDAV;


            $total_inventario_SSJOFA =  Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('gpo_owner_id', 3)
                ->get()->count();

            $registros_nuevos_SSJOFA = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 1 )
                ->where('solicitudes.gpo_nuevo_id', 3)
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $registros_cambio_nuevos_SSJOFA = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 3 )
                ->where('solicitudes.gpo_nuevo_id', 3)
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $registros_en_baja_SSJOFA = Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('gpo_owner_id', 3)
                ->whereHas('solicitud_with_baja')->get()->count();

            $registros_cambio_anteriores_SSJOFA = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 3 )
                ->where('solicitudes.gpo_actual_id', 3)
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $total_ctas_SSJOFA = $total_inventario_SSJOFA + $registros_nuevos_SSJOFA + $registros_cambio_nuevos_SSJOFA 
                - $registros_en_baja_SSJOFA - $registros_cambio_anteriores_SSJOFA;


            $total_inventario_SSCONS =  Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->whereIn('gpo_owner_id', [7, 85])
                ->get()->count();

            $registros_nuevos_SSCONS = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 1 )
                ->whereIn('solicitudes.gpo_nuevo_id', [7, 85])
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $registros_cambio_nuevos_SSCONS = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 3 )
                ->whereIn('solicitudes.gpo_nuevo_id', [7, 85])
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $registros_en_baja_SSCONS = Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->whereIn('gpo_owner_id', [7, 85])
                ->whereHas('solicitud_with_baja')->get()->count();

            $registros_cambio_anteriores_SSCONS = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 3 )
                ->whereIn('solicitudes.gpo_actual_id', [7, 85])
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $total_ctas_SSCONS = $total_inventario_SSCONS + $registros_nuevos_SSCONS + $registros_cambio_nuevos_SSCONS 
                - $registros_en_baja_SSCONS - $registros_cambio_anteriores_SSCONS;


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


            $total_inventario_SSOPER =  Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('gpo_owner_id', 6)
                ->get()->count();

            $registros_nuevos_SSOPER = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 1 )
                ->where('solicitudes.gpo_nuevo_id', 6)
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $registros_cambio_nuevos_SSOPER = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 3 )
                ->where('solicitudes.gpo_nuevo_id', 6)
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $registros_en_baja_SSOPER = Inventory_cta::where('inventory_id', $inventory_id)
                ->where('delegacion_id', $user->delegacion->id)
                ->where('gpo_owner_id', 6)
                ->whereHas('solicitud_with_baja')->get()->count();

            $registros_cambio_anteriores_SSOPER = Solicitud::where('delegacion_id', $user->delegacion->id)
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 3 )
                ->where('solicitudes.gpo_actual_id', 6)
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                )->get()->count();

            $total_ctas_SSOPER = $total_inventario_SSOPER + $registros_nuevos_SSOPER + $registros_cambio_nuevos_SSOPER 
                - $registros_en_baja_SSOPER - $registros_cambio_anteriores_SSOPER;

            return view('ctas.home_ctas', compact( 
                'primer_renglon', 
                'subdelegaciones',

                'total_ctas_Ctas',
                'total_inventario_Ctas', 
                'registros_nuevos_Ctas', 
                'registros_en_baja_Ctas', 

                'total_ctas_Genericas',
                'total_inventario_Genericas', 
                'registros_nuevos_Genericas', 
                'registros_cambio_nuevos_Genericas', 
                'registros_en_baja_Genericas', 
                'registros_cambio_anteriores_Genericas',
                
                'total_ctas_Clas',
                'total_inventario_Clas', 
                'registros_nuevos_Clas', 
                'registros_cambio_nuevos_Clas', 
                'registros_en_baja_Clas', 
                'registros_cambio_anteriores_Clas',
                
                'total_ctas_Fisca',
                'total_inventario_Fisca', 
                'registros_nuevos_Fisca', 
                'registros_cambio_nuevos_Fisca', 
                'registros_en_baja_Fisca', 
                'registros_cambio_anteriores_Fisca',
                
                'total_ctas_SVC',
                'total_inventario_SVC', 
                'registros_nuevos_SVC', 
                'registros_cambio_nuevos_SVC', 
                'registros_en_baja_SVC', 
                'registros_cambio_anteriores_SVC',

                'total_ctas_Cobranza',
                
                'total_ctas_SSJSAV',
                'total_inventario_SSJSAV', 
                'registros_nuevos_SSJSAV', 
                'registros_cambio_nuevos_SSJSAV', 
                'registros_en_baja_SSJSAV', 
                'registros_cambio_anteriores_SSJSAV',
                
                'total_ctas_SSJDAV',
                'total_inventario_SSJDAV', 
                'registros_nuevos_SSJDAV', 
                'registros_cambio_nuevos_SSJDAV', 
                'registros_en_baja_SSJDAV', 
                'registros_cambio_anteriores_SSJDAV',
                
                'total_ctas_SSJOFA',
                'total_inventario_SSJOFA', 
                'registros_nuevos_SSJOFA', 
                'registros_cambio_nuevos_SSJOFA', 
                'registros_en_baja_SSJOFA', 
                'registros_cambio_anteriores_SSJOFA',
                
                'total_ctas_SSCONS',
                'total_inventario_SSCONS', 
                'registros_nuevos_SSCONS', 
                'registros_cambio_nuevos_SSCONS', 
                'registros_en_baja_SSCONS', 
                'registros_cambio_anteriores_SSCONS',

                'total_ctas_SSADIF',
                'total_inventario_SSADIF', 
                'registros_nuevos_SSADIF', 
                'registros_cambio_nuevos_SSADIF', 
                'registros_en_baja_SSADIF', 
                'registros_cambio_anteriores_SSADIF',

                'total_ctas_SSOPER',
                'total_inventario_SSOPER', 
                'registros_nuevos_SSOPER', 
                'registros_cambio_nuevos_SSOPER', 
                'registros_en_baja_SSOPER', 
                'registros_cambio_anteriores_SSOPER',

                ) );
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

        $user = Auth::user();

        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_delegacion_id = Auth::user()->delegacion_id;
        
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
