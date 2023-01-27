<?php

namespace App\Http\Controllers;

use App\Subdelegacion;
use App\Delegacion;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class AccountListController extends Controller
{

    public function getAccountList($p_delegacion_id)
    {
        $inventory_id = env('INVENTORY_ID');

        // Get subdelegaciones
        $subdelegaciones_list =
            Subdelegacion::select('id', 'name')->where('delegacion_id', $p_delegacion_id)
                ->where('status', '<>', 0)
                ->orderBy('delegacion_id', 'asc')
                ->orderBy('num_sub', 'asc')
                ->get();

        if ( Auth::user()->hasRole('admin_dspa') )
            // Get Delegaciones (OOAD's)
            $delegaciones_list = Delegacion::select('id', 'name')
                ->where('status', '<>', 0)
                ->orderBy('id', 'asc')
                ->get();
        else
            $delegaciones_list = [];

        $delegacion_a_consultar = Delegacion::find($p_delegacion_id);

        // Get the account list from last inventory
        $active_accounts_inventory =
            DB::table('inventory_ctas AS IC')
            ->join('delegaciones AS D',   'IC.delegacion_id', '=', 'D.id')
            ->join('groups AS G',         'IC.gpo_owner_id',  '=', 'G.id')
            ->join('inventories AS I',    'IC.inventory_id',  '=', 'I.id')
            ->leftjoin('work_areas AS W', 'IC.work_area_id',  '=', 'W.id')
            ->leftjoin('siap AS SI',      'IC.install_data',  '=', 'SI.matricula')
            ->select(DB::Raw(
                'IC.cuenta      AS Cuenta,
                ""              AS Id_origen,
                "--"            AS Id,
                D.id            AS Del_id,
                D.name          AS Del_name,
                ""              AS Subdel_id,
                ""              AS Subdel_name,
                "Inventario"    AS Mov,
                IC.name         AS Nombre_origen,
                "--"            As Nombre,
                G.name          AS Gpo_actual,
                "--"            AS Gpo_nuevo,
                "--"            AS Gpo_unificado,
                IC.install_data AS Matricula_origen,
                "--"            AS Matricula,
                "--"            AS CURP_origen,
                "--"            AS CURP,
                IC.work_area_id AS Work_area_id,
                W.name          AS Work_area_name,
                I.cut_off_date  AS Fecha_mov,
                SI.adscripcion  AS Datos_siap1,
                ""              AS Datos_siap2'
            ))
            ->where('IC.inventory_id', $inventory_id);

        // ...and the list of approved changes from Solicitudes
        $active_accounts_solicitudes =
            DB::table('solicitudes AS S')
            ->join('delegaciones AS D',           'S.delegacion_id',      '=', 'D.id')
            ->join('subdelegaciones AS SUB',      'S.subdelegacion_id',   '=', 'SUB.id')
            ->join('subdelegaciones AS SUB2',     'S.delegacion_id',   '=', 'SUB2.delegacion_id')
            ->join('resultado_solicitudes AS RS', 'RS.solicitud_id',      '=', 'S.id')
            ->join('resultado_lotes AS RL',       'RS.resultado_lote_id', '=', 'RL.id')
            ->leftjoin('groups AS GA',            'S.gpo_actual_id',      '=', 'GA.id')
            ->leftjoin('groups AS GB',            'S.gpo_nuevo_id',       '=', 'GB.id')
            ->join('movimientos AS M',            'S.movimiento_id',      '=', 'M.id')
            ->leftjoin('siap AS SI',              'S.matricula',          '=', 'SI.matricula')
            ->select(DB::Raw(
                'RS.cuenta      AS Cuenta,
                RS.solicitud_id AS Id_origen,
                "--"            AS Id,
                D.id            AS Del_id,
                D.name          AS Del_name,
                SUB.id          AS Subdel_id,
                SUB.name        AS Subdel_name,
                M.name          AS Mov,
                concat(S.primer_apellido, "-", S.segundo_apellido, "-", S.nombre)
                                AS Nombre_origen,
                "--"            As Nombre,
                GA.name         AS Gpo_actual,
                GB.name         AS Gpo_nuevo,
                "--"            AS Gpo_unificado,
                S.matricula     AS Matricula_origen,
                "--"            AS Matricula,
                S.curp          AS CURP_origen,
                "--"            AS CURP,
                0               AS Work_area_id,
                ""              AS Work_area_name,
                RL.attended_at  AS Fecha_mov,
                ""              AS Datos_siap1,
                SI.adscripcion  AS Datos_siap2'
            ))
            ->whereNull('RS.rechazo_mainframe_id');

        //if we had a parameter var 'Delegational_id', add delegacion_id to the query
        if ( is_null($delegacion_a_consultar) )
            return null;
        else {
            if ( $delegacion_a_consultar->id <> 0 ) {
                $active_accounts_inventory   = $active_accounts_inventory->where('IC.delegacion_id', $delegacion_a_consultar->id);
                $active_accounts_solicitudes = $active_accounts_solicitudes->where('S.delegacion_id', $delegacion_a_consultar->id);
            }

            //Finally, make UNION
            $active_accounts_list =
                $active_accounts_solicitudes
                ->union($active_accounts_inventory)
                    ->orderby('Cuenta')
                    ->orderby('Fecha_mov')
                ->get();

            return [
                'active_accounts_list'   => $active_accounts_list,
                'delegacion_a_consultar' => $delegacion_a_consultar,
                'delegaciones_list'      => $delegaciones_list,
                'subdelegaciones_list'   => $subdelegaciones_list,
            ];
        }
    }

    public function exportAccountList($p_active_accounts_list, $p_delegacion_id, $p_bol_Del_user)
    {
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_job_id = Auth::user()->job_id;
        $user_del_id = Auth::user()->delegacion_id;

        $texto_log = 'User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id . '|Job:' . $user_job_id;

        if (    ( Auth::user()->hasRole('admin_dspa') && Gate::allows('export_lista_ctas_vigentes_gral') )
                ||
                ( Auth::user()->hasRole('capturista_delegacional') && Gate::allows( 'export_lista_ctas_vigentes_del') && ($p_delegacion_id == $user_del_id) )
            ) {
            $var = 1;
            $delimiter = ',';

            $delegacion_a_consultar = Delegacion::find($p_delegacion_id);

            if ( Auth::user()->hasRole('admin_dspa') && !$p_bol_Del_user) {
                if ($p_delegacion_id == 0)
                    $filename = 'ADMIN_Todas_CtasVig_';
                else
                    $filename = 'ADMIN_Del' . str_pad($p_delegacion_id, 2, '0', STR_PAD_LEFT) . '_CtasVig_';

                $fields = array('#', 'Solicitud_id', 'Del_id', 'Cuenta', 'Origen', 'Nombre_origen', 'Nombre', 'Nombre_final', 'Grupo', 'Matrícula', 'CURP', 'Tipo_Cta', 'Fecha_mov');
            }
            else {
                $filename = 'Del_' . str_pad($p_delegacion_id, 2, '0', STR_PAD_LEFT) . '-CtasVig ';
                $fields = array('Cuenta', 'Subdelegación', 'Nombre', 'Grupo', 'Matrícula', 'CURP', 'Tipo_Cta');
            }

            $filename = $filename . date('dMY_H:i:s') . '.csv';

            // Create a file pointer
            $f = fopen('php://memory', 'w');

            // Set column headers
            fputcsv($f, $fields, $delimiter);

            foreach ( $p_active_accounts_list as $row_active_accounts ) {

                $origen_final = '--';
                $grupo_final = $row_active_accounts->Gpo_unificado;

                if ($row_active_accounts->Id == "--") {
                    // Resultado en inventario o solicitud
                    $origen_final = $row_active_accounts->Mov;
                }
                else {
                    if ( ($row_active_accounts->Mov == "Inventario") && ($row_active_accounts->Id == "") ) {
                        // Múltiples registros en inventario
                        $origen_final = 'Múltiples registros en ' . $row_active_accounts->Mov;
                        $grupo_final = $grupo_final;
                        /* $grupo_final = $grupo_final . '+(Múltiples grupos)'; */
                    }
                    else {
                        // Resultado en solicitud e inventario
                        $origen_final = 'Solicitud e ' . $row_active_accounts->Mov;
                    }
                }

                $lineData_Admin = array(
                    $var,
                    $row_active_accounts->Id == '--' ? $row_active_accounts->Id_origen : $row_active_accounts->Id,
                    $row_active_accounts->Del_id,
                    $row_active_accounts->Cuenta,
                    $origen_final,
                    $row_active_accounts->Nombre_origen,
                    $row_active_accounts->Nombre,
                    $row_active_accounts->Nombre == '--' ? $row_active_accounts->Nombre_origen : $row_active_accounts->Nombre,
                    $grupo_final,
                    $row_active_accounts->Matricula == '--' ? $row_active_accounts->Matricula_origen : $row_active_accounts->Matricula,
                    $row_active_accounts->CURP == '--' ? $row_active_accounts->CURP_origen : $row_active_accounts->CURP,
                    $row_active_accounts->Work_area_id == 2 ? 'Cta. Genérica' : '',
                    $row_active_accounts->Fecha_mov
                );
                //dd($row_active_accounts);
                $lineData_Del = array(
                    $row_active_accounts->Cuenta,
                    $row_active_accounts->Nombre == '--' ? $row_active_accounts->Nombre_origen : $row_active_accounts->Nombre,
                    $grupo_final,
                    $row_active_accounts->Matricula == '--' ? $row_active_accounts->Matricula_origen : $row_active_accounts->Matricula,
                    $row_active_accounts->CURP == '--' ? $row_active_accounts->CURP_origen : $row_active_accounts->CURP,
                    $row_active_accounts->Work_area_id == 2 ? 'Cta. Genérica' : ''
                );

                if ( Auth::user()->hasRole('admin_dspa') && !$p_bol_Del_user)
                    $lineData = $lineData_Admin;
                else
                    $lineData = $lineData_Del;

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
        else {
            Log::warning('Sin permiso-Exportar Lista Ctas Vigentes|' . $texto_log);
            return redirect('ctas')->with('message', 'No tiene permitido exportar el Listado de Ctas Vigentes');
        }
    }
}
