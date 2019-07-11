<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Detalle_cta;
use App\Solicitud;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        $texto_log = ' User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id . '|Job:' . $user_job_id;

        Log::info('Visitando Inventario ' . $texto_log);

        if ( Gate::allows( 'ver_inventario_del') || Gate::allows( 'ver_inventario_gral') )
        {
            $inventory_id = env('INVENTORY_ID');
            $ca_group_01 = env('CA_GROUP_01');
            $ca_group_01_eq = env('CA_GROUP_01_EQ');

            $cut_off_date = Inventory::find( $inventory_id )->cut_off_date;

            //Base query new accounts
            $solicitudes = Solicitud::sortable()
                ->with(['valija',
                    'valija_oficio',
                    'delegacion',
                    'subdelegacion',
                    'movimiento',
                    'rechazo',
                    'grupo1',
                    'grupo2',
                    'lote',
                    'resultado_solicitud.rechazo_mainframe',
                    'resultado_solicitud.resultado_lote' => function ($query) use ($cut_off_date) {
                        $query->where('attended_at', '>=', $cut_off_date );
                    },
                    'resultado_solicitud' => function ($query) {
                        $query->where('rechazo_mainframe_id', NULL);
                    }])
                ->where( 'solicitudes.id', '>=', env('INITIAL_SOLICITUD_ID') )
                ->whereDate( 'solicitudes.created_at', '>', $cut_off_date )
                ->where( 'solicitudes.movimiento_id', 1 )
                ->whereHas('resultado_solicitud.resultado_lote')
                ->whereHas('resultado_solicitud');

                //
            $list_inventario =
                Detalle_cta::sortable()
                    ->with(['gpo_owner', 'inventory', 'work_area'])
                    ->where('inventory_id', $inventory_id);

            if ( $user_del_id <> env('DSPA_USER_DEL_1') ) {
                //if is a 'Delegational' user, add delegacion_id to the query
                $solicitudes = $solicitudes->where('solicitudes.delegacion_id', $user_del_id);

                $list_inventario = $list_inventario->where('delegacion_id', $user_del_id);
            }

            if ( isset( $search_word ) && Gate::allows('ver_buscar_cta_inventario') ) {
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

                $list_inventario = $list_inventario->where(function ($list_where) use ($query) {
                    $list_where
                        ->where('solicitudes.cuenta', 'like', $query)
                        ->orWhere('solicitudes.name', 'like', $query)
                        ->orWhere('solicitudes.gpo_owner.name', 'like', $query);
                });

                $texto_log .= '|Buscando:' . $search_word;
            }

            //Finally add these instructions to any query
            $solicitudes = $solicitudes->get();

            $list_inventario = $list_inventario
                    ->orderby('cuenta')
                    ->orderby('ciz_id')
                    ->paginate( env('ROWS_ON_PAGINATE') );

        }
        else {
            Log::warning('Sin permisos-Consultar Inventario ' . $texto_log);
            return redirect('ctas')->with('message', 'No tiene permitido consultar el inventario.');
        }

        return view('ctas/inventario/home_inventario',
            compact('solicitudes' ,
                    'list_inventario' ,
                    'cut_off_date',
                    'user_del_name',
                    'user_del_id') );

    }

}
