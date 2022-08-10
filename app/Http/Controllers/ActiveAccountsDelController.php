<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class ActiveAccountsDelController extends Controller
{

    public function show_active_accounts_del()
    {
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_job_id = Auth::user()->job_id;
        $user_del_id = Auth::user()->delegacion_id;
        $user_del_name = Auth::user()->delegacion->name;

        $texto_log = 'User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id . '|Job:' . $user_job_id;

        //Si cuenta con los permisos...
        if ( Gate::allows( 'ver_lista_ctas_vigentes_del') )
        {
            $inventory_id = env('INVENTORY_ID');

            // Get the account list from last inventory
            $active_accounts_inventory =
                DB::table('inventory_ctas AS IC')
                ->join('groups AS G',       'IC.gpo_owner_id', '=', 'G.id')
                ->join('inventories AS I',  'IC.inventory_id', '=', 'I.id')
                    ->select(DB::Raw(
                        'IC.cuenta          AS Cuenta,
                        "Inventario"        AS Mov,
                        IC.name             AS Nombre,
                        G.name              AS Gpo_actual,
                        "--"                AS Gpo_nuevo,
                        "--"                AS Gpo_unificado,
                        IC.install_data     AS Matricula,
                        I.cut_off_date      AS Fecha_mov'
                    ))
            ->where('IC.inventory_id', $inventory_id);

            // ...and the list of approved changes from Solicitudes
            $active_accounts_solicitudes =
                DB::table('solicitudes AS S')
                ->join('resultado_solicitudes AS RS', 'S.id', '=', 'RS.solicitud_id')
                ->join('resultado_lotes AS RL', 'RS.resultado_lote_id', '=', 'RL.id')
                ->leftjoin('groups AS GA', 'S.gpo_actual_id', '=', 'GA.id')
                ->leftjoin('groups AS GB', 'S.gpo_nuevo_id',  '=', 'GB.id')
                ->join('movimientos AS M', 'S.movimiento_id', '=', 'M.id')
                    ->select(DB::Raw(
                        'RS.cuenta      AS Cuenta,
                        M.name          AS Mov,
                        concat(S.primer_apellido, " ", S.segundo_apellido, " ", S.nombre)
                                        AS Nombre,
                        GA.name         AS Gpo_actual,
                        GB.name         AS Gpo_nuevo,
                        "--"            AS Gpo_unificado,
                        S.matricula     AS Matricula,
                        RL.attended_at  AS Fecha_mov'
                    ))
            ->whereNull('RS.rechazo_mainframe_id');

            //if is a 'Delegational' user, add delegacion_id to the query
            if ( $user_del_id <> env('DSPA_USER_DEL_1') ) {
                $active_accounts_inventory = $active_accounts_inventory->where('IC.delegacion_id', $user_del_id);
                $active_accounts_solicitudes = $active_accounts_solicitudes->where('S.delegacion_id', $user_del_id);
            }

            //Finally, make UNION
            $active_accounts = $active_accounts_solicitudes
                ->union($active_accounts_inventory)
                    ->orderby('Cuenta')
                    ->orderby('Fecha_mov')
                ->get();

            // Filtrar los registros:
            $registro_anterior = NULL;
            $active_accounts_list = [];
            $grupos_a_eliminar = ["SSCLAS", "SSCFIZ", "DDSUBD", "SSAREE"];

            // Check each record on this ordenated list (this is important, ORDER BY), and create new array with only active accounts
            foreach ($active_accounts as $registro ) {
                $registro_actual = $registro;

                // If we have data to check on registro_anterior...
                if (isset($registro_anterior->Cuenta)) {
                    // ...Check if it's another account, check if it's an ALTA, CAMBIO, INVENTARIO, ...
                    // ...to keep this record or not
                    if ($registro_actual->Cuenta <> $registro_anterior->Cuenta) {
                        if ($registro_anterior->Mov <> "BAJA") {

                            // Show only the last group
                            if ($registro_anterior->Mov == "Inventario")
                                $registro_anterior->Gpo_unificado = $registro_anterior->Gpo_actual;
                            else
                                $registro_anterior->Gpo_unificado = $registro_anterior->Gpo_nuevo;

                            // It's not BAJA and it's an Afiliación Group, we keep this record on the new array
                            if  ( !( in_array($registro_anterior->Gpo_unificado, $grupos_a_eliminar) ) )
                                // Finally, add the record data to the final list
                                array_push($active_accounts_list, $registro_anterior);
                        }
                    }
                }
                $registro_anterior = $registro_actual;
            }

            $total_active_accounts = count($active_accounts_list);
        }
        else {
            Log::warning('Sin permiso-Consultar Lista Ctas Vigentes-Del|' . $texto_log);
            return redirect('ctas')->with('message', 'No tiene permitido consultar el Listado de Ctas Vigentes-Del.');
        }

        Log::info('Ver Lista Ctas Vigentes-Del|' . $texto_log);

        return view('ctas/inventario/home_active_accounts_del',
            compact('active_accounts_list',
                    'total_active_accounts',
                    'user_del_name',
                    'user_del_id') );
    }
}
