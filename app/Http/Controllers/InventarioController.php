<?php

namespace App\Http\Controllers;

use App\Detalle_cta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InventarioController extends Controller
{

    public function show(Message $message)
    {

        return view('messages.show', [
            'message' => $message
        ]);
    }

    public function home() {

        $user = Auth::user()->name;
        $del_id = Auth::user()->delegacion_id;
        $del_name = Auth::user()->delegacion->name;

        Log::info('Visitando Inventario. Usuario:' . $user . '|Del:(' . $del_id . ')-' . $del_name);

        $listado_detalle_ctas =
            Detalle_cta::select('cuenta', 'name', 'gpo_owner_id', 'work_area_id', 'install_data')->where('delegacion_id', $del_id)->orderBy('work_area_id', 'cuenta')->distinct()->get();

        $total_detalle_ctas = $listado_detalle_ctas->count();

        $detalle_cta = Detalle_cta::find(1);

        return view('ctas/inventario/show', [
            'listado_detalle_ctas' => $listado_detalle_ctas,
            'total_detalle_ctas' => $total_detalle_ctas,
            'inventory' => $detalle_cta->inventory,
            'del_id' => $del_id,
            'del_name' => $del_name,
        ]);
    }
}
