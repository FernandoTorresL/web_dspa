<?php

namespace App\Http\Controllers;

use App\Detalle_cta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class InventarioController extends Controller
{

    public function show(Message $message)
    {
        return view('messages.show', [
                'message' => $message
            ]);
    }

    public function home() {

        //Get delegation_id
        $del = Auth::user()->delegacion_id;

        Log::info('Visitando Inventario. User:' . Auth::user()->name . '|Del:' . $del );

        if (Gate::allows('ver_inventario_del')) {
            $list_inventario =
                Detalle_cta::sortable()
                    //->select('cuenta', 'ciz_id', 'inventory_id', 'name', 'gpo_owner_id', 'work_area_id', 'install_data')
                    ->with(['gpo_owner', 'inventory', 'work_area'])
                    ->where('inventory_id', 30)
                    ->where('delegacion_id', $del)
                    ->orderby('work_area_id', 'desc')
                    ->orderby('cuenta')
                    ->orderby('ciz_id')
                    ->paginate(100);
            }
        elseif (Gate::allows('ver_inventario_gral')) {
            $list_inventario =
                Detalle_cta::sortable()
                    //->select('cuenta', 'ciz_id', 'inventory_id', 'name', 'gpo_owner_id', 'work_area_id', 'install_data')
                    ->with(['gpo_owner', 'inventory', 'work_area'])
                    ->where('inventory_id', 30)
                    ->orderby('work_area_id', 'desc')
                    ->orderby('cuenta')
                    ->orderby('ciz_id')
                    ->paginate(500);
        }
        else {
            Log::info('Sin permiso-Consultar inventario. User:' . Auth::user()->name . '|Del:' . $del);

            abort(403,'No tiene permitido ver este listado');
        }

        return view('ctas/inventario/show', compact('list_inventario' ));

    }
}
