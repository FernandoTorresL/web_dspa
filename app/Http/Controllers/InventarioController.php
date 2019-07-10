<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Detalle_cta;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class InventarioController extends Controller
{

    public function home()
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

            $list_inventario =
                Detalle_cta::sortable()
                    ->with(['gpo_owner', 'inventory', 'work_area'])
                    ->where('inventory_id', $inventory_id)
                    ->where('delegacion_id', $user_del_id)
                    ->orderby('cuenta')
                    ->orderby('ciz_id')
                    ->paginate( env('ROWS_ON_PAGINATE') );

            $cut_off_date = Inventory::find( $inventory_id )->cut_off_date;
        }
        else {
            Log::warning('Sin permisos-Consultar Inventario ' . $texto_log);
            return redirect('ctas')->with('message', 'No tiene permitido consultar el inventario.');
        }

        return view('ctas/inventario/show',
            compact('list_inventario' ,
                    'cut_off_date',
                    'user_del_name',
                    'user_del_id') );

    }
    
}
