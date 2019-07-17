<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Inventory_cta;
use App\Solicitud;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class InventarioController extends Controller
{

    public function search_inventario(Request $request)
    {
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_job_id = Auth::user()->job_id;
        $user_del_id = Auth::user()->delegacion_id;
        $user_del_name = Auth::user()->delegacion->name;

        $search_word = $request->input('search_word');
        $texto_log = ' User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id . '|Job:' . $user_job_id;

        if ( Gate::allows( 'ver_inventario_del') || Gate::allows( 'ver_inventario_gral') )
        {
            $inventory_id = env('INVENTORY_ID');

            $cut_off_date = Inventory::find( $inventory_id )->cut_off_date;

            //Base query new accounts
            $solicitudes = Solicitud::with( ['gpo_nuevo', 'resultado_solicitud'] )
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->where( 'solicitudes.movimiento_id', 1 )
                ->whereHas( 'resultado_solicitud.resultado_lote', function ( $list_where ) use ( $cut_off_date ) {
                    $list_where
                        ->where( 'resultado_lotes.attended_at', '>', $cut_off_date )
                    ->whereNull( 'rechazo_mainframe_id'); } 
                );

            $new_inventory_list = Inventory_cta::sortable('cuenta')
                ->with(['gpo_owner', 'work_area', 'registros_en_baja'])
                ->where('inventory_id', $inventory_id);

            //if is a 'Delegational' user, add delegacion_id to the query
            if ( $user_del_id <> env('DSPA_USER_DEL_1') ) {
                $solicitudes = $solicitudes->where('solicitudes.delegacion_id', $user_del_id);

                $new_inventory_list = $new_inventory_list->where('delegacion_id', $user_del_id);
            }

            $total_inventario = $new_inventory_list->count();

            //And if there's a 'search word', add that word to the query and to the log
            if ( isset( $search_word ) && Gate::allows('ver_buscar_cta_inventario') ) {
                
                $query = '%' . $search_word . '%';

                $new_inventory_list = $new_inventory_list->where(function ($list_where) use ($query) {
                    $list_where
                        ->where('cuenta', 'like', $query)
                        ->orWhere('name', 'like', $query)
                        ->orWhere('install_data', 'like', $query)
                        ->orwhereHas( 'gpo_owner', function ( $list_where2 ) use ($query) {
                            $list_where2
                                ->where( 'groups.name', 'like', $query ); }
                        )
                        ->orwhereHas( 'work_area', function ( $list_where3 ) use ($query) {
                            $list_where3
                                ->where( 'work_areas.name', 'like', $query ); }
                        );
                });

                $texto_log .= '|Buscando:' . strtoupper($search_word);
            }

            //Finally add these instructions to any query
            $solicitudes = $solicitudes->orderby('cuenta')->get();

            $new_inventory_list = $new_inventory_list->paginate( env('ROWS_ON_PAGINATE') );

        }
        else {
            Log::warning('Sin permisos-Consultar Inventario ' . $texto_log);
            return redirect('ctas')->with('message', 'No tiene permitido consultar el inventario.');
        }

        Log::info('Ver Inventario ' . $texto_log);
        return view('ctas/inventario/home_inventario',
            compact('solicitudes' ,
                    'new_inventory_list',
                    'total_inventario',
                    'cut_off_date',
                    'user_del_name',
                    'user_del_id',
                    'search_word') );

    }

}
