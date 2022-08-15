<?php

namespace App\Http\Controllers;
use App\Delegacion;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class ExportActiveAccountsGralController extends Controller
{

    public function export_active_accounts_gral ($p_delegacion_id)
    {
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_job_id = Auth::user()->job_id;
        $user_del_id = Auth::user()->delegacion_id;
        $user_del_name = Auth::user()->delegacion->name;

        $texto_log = 'User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id . '|Job:' . $user_job_id;

        //Si cuenta con los permisos...
        if ( Auth::user()->hasRole('admin_dspa') && Gate::allows('export_lista_ctas_vigentes_gral') )
        {
            $inventory_id = env('INVENTORY_ID');

            // Get Delegaciones (OOAD's)
            $delegaciones_gral_list =
                Delegacion::where('status', '<>', 0)
                    ->orderBy('id', 'asc')
                    ->get();

            $delegacion_a_consultar =
                Delegacion::find($p_delegacion_id);

            // Get the account list from last inventory
            $active_accounts_gral_inventory =
                DB::table('inventory_ctas AS IC')
                ->join('delegaciones AS D',   'IC.delegacion_id', '=', 'D.id')
                ->join('groups AS G',         'IC.gpo_owner_id',  '=', 'G.id')
                ->join('inventories AS I',    'IC.inventory_id',  '=', 'I.id')
                ->leftjoin('work_areas AS W', 'IC.work_area_id',  '=', 'W.id')
                ->select(DB::Raw(
                    'IC.cuenta      AS Cuenta,
                    ""              AS Id,
                    D.id            AS Del_id,
                    D.name          AS Del_name,
                    "Inventario"    AS Mov,
                    IC.name         AS Nombre,
                    G.name          AS Gpo_actual,
                    "--"            AS Gpo_nuevo,
                    "--"            AS Gpo_unificado,
                    IC.install_data AS Matricula,
                    IC.work_area_id AS Work_area_id,
                    W.name          AS Work_area_name,
                    I.cut_off_date  AS Fecha_mov'
                ))
            ->where('IC.inventory_id', $inventory_id);

            // ...and the list of approved changes from Solicitudes
            $active_accounts_gral_solicitudes =
                DB::table('solicitudes AS S')
                ->join('delegaciones AS D',           'S.delegacion_id',      '=', 'D.id')
                ->join('resultado_solicitudes AS RS', 'RS.solicitud_id',       '=', 'S.id')
                ->join('resultado_lotes AS RL',       'RS.resultado_lote_id', '=', 'RL.id')
                ->leftjoin('groups AS GA',            'S.gpo_actual_id',      '=', 'GA.id')
                ->leftjoin('groups AS GB',            'S.gpo_nuevo_id',       '=', 'GB.id')
                ->join('movimientos AS M',            'S.movimiento_id',      '=', 'M.id')
                ->select(DB::Raw(
                    'RS.cuenta      AS Cuenta,
                    RS.solicitud_id AS Id,
                    D.id            AS Del_id,
                    D.name          AS Del_name,
                    M.name          AS Mov,
                    concat(S.primer_apellido, "-", S.segundo_apellido, "-", S.nombre)
                                    AS Nombre,
                    GA.name         AS Gpo_actual,
                    GB.name         AS Gpo_nuevo,
                    "--"            AS Gpo_unificado,
                    S.matricula     AS Matricula,
                    0               AS Work_area_id,
                    ""              AS Work_area_name,
                    RL.attended_at  AS Fecha_mov'
                ))
            ->whereNull('RS.rechazo_mainframe_id');

            //if we had a parameter var 'Delegational_id', add delegacion_id to the query
            if ( $delegacion_a_consultar->id <> 0 ) {
                $active_accounts_gral_inventory =
                    $active_accounts_gral_inventory->where('IC.delegacion_id', $delegacion_a_consultar->id);
                $active_accounts_gral_solicitudes =
                    $active_accounts_gral_solicitudes->where('S.delegacion_id', $delegacion_a_consultar->id);
            }

            //Finally, make UNION
            $active_accounts_gral = $active_accounts_gral_solicitudes
                ->union($active_accounts_gral_inventory)
                    ->orderby('Cuenta')
                    ->orderby('Fecha_mov')
                ->get();

            // Filtrar los registros:
            $registro_anterior = NULL;
            $active_accounts_gral_list = [];
            $grupos_a_eliminar = explode(',', env('GROUPS_EXC'));

            // Check each record on this ordenated list (this is important, ORDER BY), and create new array with only active accounts
            foreach ($active_accounts_gral as $registro ) {
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
                            if  ( !( in_array($registro_anterior->Gpo_unificado, $grupos_a_eliminar) ) ) {
                                // Finally, add the record data to the final list
                                array_push($active_accounts_gral_list, $registro_anterior);

                            }
                        }
                    }
                }
                $registro_anterior = $registro_actual;
            }

        }
        else {
            Log::warning('Sin permiso-Exportar Lista Ctas Vigentes Nacional|' . $texto_log);
            return redirect('ctas')->with('message', 'No tiene permitido exportar el Listado de Ctas Vigentes Nacional.');
        }

        Log::info('Exportar Lista Ctas Vigentes Nacional|' . $texto_log);

        $var = 1;
        $delimiter = ",";
        $num_delegacion = str_pad($delegacion_a_consultar->id, 2, '0', STR_PAD_LEFT);

        if ( $delegacion_a_consultar->id == 0 )
            $filename = "ADMIN-Nacional-CtasVig " . date('dMY H:i:s') . ".csv";
        else
            $filename = "ADMIN-" . $num_delegacion . "-CtasVig " . date('dMY H:i:s') . ".csv";

        // Create a file pointer
        $f = fopen('php://memory', 'w');

        // Set column headers
        $fields = array('#', 'USER-ID', 'Id', 'Del_id', 'Del_name', 'Origen', 'Nombre', 'Grupo actual', 'Gpo_nuevo', 'Gpo_unificado', 'Matrícula', 'work_area_id', 'work_area_name', 'Fecha_mov');
        fputcsv($f, $fields, $delimiter);

        foreach ( $active_accounts_gral_list as $row_active_accounts ) {
            $lineData = array(
                $var,
                $row_active_accounts->Cuenta,
                $row_active_accounts->Id,
                $row_active_accounts->Del_id,
                $row_active_accounts->Del_name,
                $row_active_accounts->Mov,
                $row_active_accounts->Nombre,
                $row_active_accounts->Gpo_actual,
                $row_active_accounts->Gpo_nuevo,
                $row_active_accounts->Gpo_unificado,
                $row_active_accounts->Matricula,
                $row_active_accounts->Work_area_id,
                $row_active_accounts->Work_area_name,
                $row_active_accounts->Fecha_mov,
            );

            fputcsv($f, $lineData, $delimiter);
            $var += 1;
        }

        // Move back to beginning of file
        fseek($f, 0);

        // Set headers to download file rather than displayed
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');

        // Output all remaining data on a file pointer
        fpassthru($f);
    }
}
