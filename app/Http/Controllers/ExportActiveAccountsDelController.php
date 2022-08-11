<?php

namespace App\Http\Controllers;
use App\Subdelegacion;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

use Illuminate\Http\Request;

class ExportActiveAccountsDelController extends Controller
{
    public function export_active_accounts ()
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

            // Get subdelegaciones
            $subdelegaciones =
                Subdelegacion::where('delegacion_id', $user_del_id)
                    ->where('status', '<>', 0)
                    ->orderBy('num_sub', 'asc')
                    ->get();

            // Get the account list from last inventory
            $active_accounts_inventory =
                DB::table('inventory_ctas AS IC')
                ->join('groups AS G',       'IC.gpo_owner_id', '=', 'G.id')
                ->join('inventories AS I',  'IC.inventory_id', '=', 'I.id')
                ->leftjoin('work_areas AS W',  'IC.work_area_id', '=', 'W.id')
                ->select(DB::Raw(
                    'IC.cuenta          AS Cuenta,
                    ""                  AS id,
                    "Inventario"        AS Mov,
                    IC.name             AS Nombre,
                    G.name              AS Gpo_actual,
                    "--"                AS Gpo_nuevo,
                    "--"                AS Gpo_unificado,
                    IC.install_data     AS Matricula,
                    IC.work_area_id     AS Work_area_id,
                    W.name              AS Work_area,
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
                    RS.solicitud_id AS id,
                    M.name          AS Mov,
                    concat(S.primer_apellido, " ", S.segundo_apellido, " ", S.nombre)
                                    AS Nombre,
                    GA.name         AS Gpo_actual,
                    GB.name         AS Gpo_nuevo,
                    "--"            AS Gpo_unificado,
                    S.matricula     AS Matricula,
                    0               AS Work_area_id,
                    ""              AS Work_area,
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
            $grupos_a_eliminar = ["SSCLAS", "SSCFIZ", "DDSUBD", "SSAREE", "EEAPM", "EECOP", "EESCF", "EEEMI"];

            $total_ctas_SSJSAV = 0;
            $total_ctas_SSJDAV = 0;
            $total_ctas_SSJOFA = 0;
            $total_ctas_SSJVIG = 0;

            $total_ctas_SSCONS = 0;
            $total_ctas_SSADIF = 0;
            $total_ctas_SSOPER = 0;

            $total_ctas_SSCERT = 0;
            $total_ctas_SSCAMC = 0;
            $total_ctas_SSCAUM = 0;
            $total_ctas_SSCAPC = 0;
            $total_ctas_SSCAMP = 0;

            $total_ctas_Genericas = 0;
            $total_ctas_SVC       = 0;

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
                            if  ( !( in_array($registro_anterior->Gpo_unificado, $grupos_a_eliminar) ) ) {
                                // Finally, add the record data to the final list
                                array_push($active_accounts_list, $registro_anterior);

                            }
                        }
                    }
                }
                $registro_anterior = $registro_actual;
            }

        }
        else {
            Log::warning('Sin permiso-Exportar Lista Ctas Vigentes-Del|' . $texto_log);
            return redirect('ctas')->with('message', 'No tiene permitido exportar el Listado de Ctas Vigentes-Del.');
        }

        Log::info('Exportar Lista Ctas Vigentes-Del|' . $texto_log);

        //Code to export data to csv
        /* <?php
        $out = fopen('php://output', 'w');
        fputcsv($out, array('this','is some', 'csv "stuff", you know.'));
        fclose($out);
        ?> */


        $var = 1;
        $delimiter = ",";
        $filename = "CtasVigentesDel" . $user_del_id . "_" . date('d-M-Y') . ".csv";

        // Create a file pointer
        $f = fopen('php://memory', 'w');

        // Set column headers
        $fields = array('#', 'USER-ID', 'Origen', 'Nombre', 'Grupo', 'Matrícula', 'Tipo_Cta');
        fputcsv($f, $fields, $delimiter);

        foreach ( $active_accounts_list as $row_active_accounts ) {
            $lineData = array(
                $var,
                $row_active_accounts->Cuenta,
                $row_active_accounts->Mov,
                $row_active_accounts->Nombre,
                $row_active_accounts->Gpo_unificado,
                $row_active_accounts->Matricula,
                $row_active_accounts->Work_area_id == 2 ? 'Cta. Genérica' : "");

            fputcsv($f, $lineData, $delimiter);
            $var += 1;
        }

        // Move back to beginning of file
        fseek($f, 0);

        // Set headers to download file rather than displayed
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        //fclose($f);

        //output all remaining data on a file pointer
        fpassthru($f);
    }
}
