<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class ActiveAccountsController extends Controller
{

    public function show_active_accounts(Request $request)
    {
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_job_id = Auth::user()->job_id;
        $user_del_id = Auth::user()->delegacion_id;
        $user_del_name = Auth::user()->delegacion->name;

        $texto_log = ' User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id . '|Job:' . $user_job_id;

        if ( Gate::allows( 'ver_inventario_del') || Gate::allows( 'ver_inventario_gral') )
        {
            $inventory_id = env('INVENTORY_ID');

            $active_accounts_inventory = DB::table('inventory_ctas')
            ->join('groups', 'inventory_ctas.gpo_owner_id', '=', 'groups.id')
            ->join('inventories', 'inventory_ctas.inventory_id', '=', 'inventories.id')
            ->select(DB::Raw('inventory_ctas.cuenta AS Cuenta, "Inventario" AS Mov, inventory_ctas.name AS Nombre, groups.name AS Gpo_actual, "--" AS Gpo_nuevo, install_data AS Matricula, cut_off_date AS Fecha_mov'))
            ->where('inventory_id', $inventory_id)
            ->where('delegacion_id', $user_del_id)
            ->orderby('inventory_ctas.cuenta');

            // dd($active_accounts_inventory);

            $active_accounts_solicitudes = DB::table('solicitudes')
            ->join('resultado_solicitudes', 'solicitudes.id', '=', 'resultado_solicitudes.solicitud_id')
            ->join('resultado_lotes', 'resultado_solicitudes.resultado_lote_id', '=', 'resultado_lotes.id')
            ->leftjoin('groups AS GA', 'solicitudes.gpo_actual_id', '=', 'GA.id')
            ->leftjoin('groups AS GB', 'solicitudes.gpo_nuevo_id', '=', 'GB.id')
            ->join('movimientos', 'solicitudes.movimiento_id', '=', 'movimientos.id')
            ->select(DB::Raw('solicitudes.cuenta AS Cuenta, movimientos.name AS Mov, 
                concat(solicitudes.nombre, " ", solicitudes.primer_apellido, " ", solicitudes.segundo_apellido) AS Nombre, GA.name AS Gpo_actual, GB.name AS Gpo_nuevo, solicitudes.matricula AS Matricula, resultado_lotes.attended_at AS Fecha_mov'))
            ->whereNull('resultado_solicitudes.rechazo_mainframe_id')
            ->where('delegacion_id', $user_del_id)
            ->union($active_accounts_inventory)
            ->orderby('Cuenta')
            ->orderby('Fecha_mov')
            ->get();

            // dd($active_accounts_solicitudes);


            $active_accounts_inventory = DB::table('inventory_ctas')
            ->join('groups', 'inventory_ctas.gpo_owner_id', '=', 'groups.id')
            ->join('inventories', 'inventory_ctas.inventory_id', '=', 'inventories.id')
            ->select(DB::Raw('inventory_ctas.cuenta AS Cuenta, "Inventario" AS Mov, inventory_ctas.name AS Nombre, groups.name AS Gpo_actual, "--" AS Gpo_nuevo, install_data AS Matricula, cut_off_date AS Fecha_mov'))
            ->where('inventory_id', $inventory_id);

            // dd($active_accounts_inventory);

            $active_accounts_solicitudes = DB::table('solicitudes')
            ->join('resultado_solicitudes', 'solicitudes.id', '=', 'resultado_solicitudes.solicitud_id')
            ->join('resultado_lotes', 'resultado_solicitudes.resultado_lote_id', '=', 'resultado_lotes.id')
            ->leftjoin('groups AS GA', 'solicitudes.gpo_actual_id', '=', 'GA.id')
            ->leftjoin('groups AS GB', 'solicitudes.gpo_nuevo_id', '=', 'GB.id')
            ->join('movimientos', 'solicitudes.movimiento_id', '=', 'movimientos.id')
            ->select(DB::Raw('solicitudes.cuenta AS Cuenta, movimientos.name AS Mov, 
                concat(solicitudes.nombre, " ", solicitudes.primer_apellido, " ", solicitudes.segundo_apellido) AS Nombre, GA.name AS Gpo_actual, GB.name AS Gpo_nuevo, solicitudes.matricula AS Matricula, resultado_lotes.attended_at AS Fecha_mov'))
            ->whereNull('resultado_solicitudes.rechazo_mainframe_id');

            //dd($active_accounts_solicitudes);

            //if is a 'Delegational' user, add delegacion_id to the query
            if ( $user_del_id <> env('DSPA_USER_DEL_1') ) {
                $active_accounts_inventory = $active_accounts_inventory->where('delegacion_id', $user_del_id);
                $active_accounts_solicitudes = $active_accounts_solicitudes->where('delegacion_id', $user_del_id);
            }

            $total_active_accounts = $active_accounts_inventory->count() + $active_accounts_solicitudes->count();

            //Finally, make UNION
            $active_accounts = $active_accounts_solicitudes
                ->union($active_accounts_inventory)
                ->orderby('Cuenta')
                ->orderby('Fecha_mov')
                ->get();

            //dd($active_accounts);

            //$active_accounts = $active_accounts->paginate( env('ROWS_ON_PAGINATE') );
        }
        else {
            Log::warning('Sin permisos-Consultar Listado de Cuentas Vigentes ' . $texto_log);
            return redirect('ctas')->with('message', 'No tiene permitido consultar el listado.');
        }

        Log::info('Ver Listado de Cuentas Vigentes ' . $texto_log);

        return view('ctas/inventario/home_active_accounts',
            compact('active_accounts',
                    'total_active_accounts',
                    'user_del_name',
                    'user_del_id') );
    }
}
