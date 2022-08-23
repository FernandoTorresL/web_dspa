<?php

namespace App\Http\Controllers;

use App\Subdelegacion;

use App\Http\Controllers\AccountListController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class ActiveAccountsDelController extends Controller
{

    public function show_active_accounts_del($p_delegacion_id)
    {
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_job_id = Auth::user()->job_id;
        $user_del_id = Auth::user()->delegacion_id;
        $user_del_name = Auth::user()->delegacion->name;

        $texto_log = 'User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id . '|Job:' . $user_job_id;

        //Si cuenta con los permisos...
        if ( Auth::user()->hasRole('capturista_delegacional') && Gate::allows( 'ver_lista_ctas_vigentes_del') && ($p_delegacion_id == $user_del_id) )
        {
            $AccountListController = new AccountListController;
            $accounts_list_items = $AccountListController->getAccountList($p_delegacion_id);

            // Filtrar los registros:
            $registro_anterior = NULL;
            $active_accounts_list = [];
            $grupos_a_eliminar = explode(',', env('GROUPS_EXC'));

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
            $total_ctas_TTD       = 0;

            // Check each record on this ordenated list (this is important, ORDER BY), and create new array with only active accounts
            if ( is_null($accounts_list_items) )
                return redirect('ctas')
                ->with('message', 'No existe información para mostrar');
            else {
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

                                    switch($registro_anterior->Gpo_unificado) {
                                        case 'SSJSAV': $total_ctas_SSJSAV += 1; break;
                                        case 'SSJDAV': $total_ctas_SSJDAV += 1; break;
                                        case 'SSJOFA': $total_ctas_SSJOFA += 1; break;
                                        case 'SSJVIG': $total_ctas_SSJVIG += 1; break;

                                        case 'SSCONS': $total_ctas_SSCONS += 1; break;
                                        case 'SSADIF': $total_ctas_SSADIF += 1; break;
                                        case 'SSOPER': $total_ctas_SSOPER += 1; break;

                                        case 'SSCERT': $total_ctas_SSCERT += 1; break;
                                        case 'SSCAMC': $total_ctas_SSCAMC += 1; break;
                                        case 'SSCAUM': $total_ctas_SSCAUM += 1; break;
                                        case 'SSCAPC': $total_ctas_SSCAPC += 1; break;
                                        case 'SSCAMP': $total_ctas_SSCAMP += 1; break;

                                        case 'TSEM':   $total_ctas_SVC    += 1; break;
                                    }

                                    if ( ($registro_anterior->Matricula == 'TTD') || ($registro_anterior->Matricula_origen == 'TTD') )
                                        $total_ctas_TTD += 1;

                                    if ($registro_anterior->Work_area_id == '2')
                                        $total_ctas_Genericas += 1;
                                }
                            }
                        }
                        else if ($registro_actual->Mov == "Inventario") {
                            $registro_actual->Id = $registro_anterior->Id_origen;
                            $registro_actual->Nombre = $registro_anterior->Nombre_origen;
                            $registro_actual->Matricula = $registro_anterior->Matricula_origen;
                            $registro_actual->CURP = $registro_anterior->CURP_origen;
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
            $total_active_accounts = count($active_accounts_list);

            $subdelegaciones = $accounts_list_items['subdelegaciones_list'];
            $delegacion_a_consultar = $accounts_list_items['delegacion_a_consultar'];

        }
        else {
            Log::warning('Sin permiso-Consultar Lista Ctas Vigentes-Del|' . $texto_log);
            return redirect('ctas')->with('message', 'No tiene permitido consultar el Listado de Ctas Vigentes de otra OOAD');
        }

        Log::info('Ver Lista Ctas Vigentes-Del|' . $texto_log);

        return view('ctas/inventario/home_active_accounts_del',
            compact('active_accounts_list',
                    'total_active_accounts',
                    'user_del_name',
                    'user_del_id',
                    'subdelegaciones',
                    'delegacion_a_consultar',

                    'total_ctas_SSJSAV',
                    'total_ctas_SSJDAV',
                    'total_ctas_SSJOFA',
                    'total_ctas_SSJVIG',

                    'total_ctas_SSCONS',
                    'total_ctas_SSADIF',
                    'total_ctas_SSOPER',

                    'total_ctas_SSCERT',
                    'total_ctas_SSCAMC',
                    'total_ctas_SSCAUM',
                    'total_ctas_SSCAPC',
                    'total_ctas_SSCAMP',

                    'total_ctas_Genericas',
                    'total_ctas_SVC',
                    'total_ctas_TTD'
            )
        );
    }
}
