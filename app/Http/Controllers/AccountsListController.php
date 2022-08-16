<?php

namespace App\Http\Controllers;
use App\Subdelegacion;
use App\Delegacion;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class AccountsListController extends Controller
{

    public function getAccountsListController($p_delegacion_id)
    {
        $inventory_id = env('INVENTORY_ID');

        // Get subdelegaciones
        $subdelegaciones_list =
            Subdelegacion::where('delegacion_id', $p_delegacion_id)
                ->where('status', '<>', 0)
                ->orderBy('delegacion_id', 'asc')
                ->orderBy('num_sub', 'asc')
                ->get();

        // Get Delegaciones (OOAD's)
        $delegaciones_list =
            Delegacion::where('status', '<>', 0)
                ->orderBy('id', 'asc')
                ->get();

        $delegacion_a_consultar =
            Delegacion::find($p_delegacion_id);

        // Get the account list from last inventory
        $active_accounts_inventory =
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
        $active_accounts_solicitudes =
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

/*         //if is a 'Delegational' user, add delegacion_id to the query
        if ( $user_del_id <> env('DSPA_USER_DEL_1') ) {
            $active_accounts_inventory = $active_accounts_inventory->where('IC.delegacion_id', $user_del_id);
            $active_accounts_solicitudes = $active_accounts_solicitudes->where('S.delegacion_id', $user_del_id);
        } */

        //if we had a parameter var 'Delegational_id', add delegacion_id to the query
        if ( $delegacion_a_consultar->id <> 0 ) {
            $active_accounts_inventory =
                $active_accounts_inventory
                ->where('IC.delegacion_id', $delegacion_a_consultar->id);
            $active_accounts_solicitudes =
                $active_accounts_solicitudes
                ->where('S.delegacion_id', $delegacion_a_consultar->id);
        }

        //Finally, make UNION
        $active_accounts_list =
            $active_accounts_solicitudes
            ->union($active_accounts_inventory)
                ->orderby('Cuenta')
                ->orderby('Fecha_mov')
            ->get();

        return [
            'active_accounts_list'  => $active_accounts_list,
            'delegacion_a_consultar' => $delegacion_a_consultar,
            'delegaciones_list'     => $delegaciones_list,
            'subdelegaciones_list'  => $subdelegaciones_list,
        ];
    }
}
