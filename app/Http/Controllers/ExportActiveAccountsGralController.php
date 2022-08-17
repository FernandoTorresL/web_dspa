<?php

namespace App\Http\Controllers;

use App\Subdelegacion;
use App\Delegacion;

use App\Http\Controllers\AccountListController;

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
        if ( Auth::user()->hasRole('admin_dspa') && Gate::allows('export_lista_ctas_vigentes_gral') ) {
            $AccountListController = new AccountListController;
            // Call to this function to get AccountList
            $accounts_list_items = $AccountListController->getAccountList($p_delegacion_id);

            // Filtrar los registros:
            $registro_anterior = NULL;
            $active_accounts_list = [];
            $grupos_a_eliminar = explode(',', env('GROUPS_EXC'));

            // Check each record on this ordenated list (this is important, ORDER BY), and create new array with only active accounts
            foreach ($accounts_list_items['active_accounts_list'] as $registro ) {
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
                    // If there's a connect, it has to be added too
                    else if ($registro_anterior->Mov == "CONNECT") {
                        $registro_anterior->Gpo_unificado = $registro_anterior->Gpo_nuevo;
                        array_push($active_accounts_list, $registro_anterior);
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

        $AccountListController = new AccountListController;
        // Call to method to export accountlist
        $AccountListController->exportAccountList($active_accounts_list, $p_delegacion_id, FALSE);
    }
}
