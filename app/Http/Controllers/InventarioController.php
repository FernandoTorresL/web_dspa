<?php

namespace App\Http\Controllers;

use App\Detalle_cta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        Log::info('Visitando Inventario. Usuario:' . Auth::user()->name . '|Del:(' . Auth::user()->delegacion_id . ')-' . Auth::user()->delegacion->descripcion);


//        INSERT INTO detalle_ctas (inventario_id, cuenta, ciz_id, delegacion_id, gpo_owner_id, gpo_default_id, area_id, name, created, passdate, passint, attribute, last_access, install_data, model, comment) (SELECT inventario_id, cuenta, ciz_id, delegacion_id, gpo_owner_id, gpo_default_id, area_id, nombre, created, passdate, passint, attribute, last_access, install_data, model, comentario FROM dd_dspa_new_web.detalle_ctas WHERE delegacion_id = 1)

        $listado_detalle_ctas =
            Detalle_cta::select('cuenta', 'name', 'gpo_owner_id', 'area_id', 'install_data')->where('delegacion_id', Auth::user()->delegacion_id)->orderBy('area_id')->orderBy('cuenta')->distinct()->get(30);

        $total_detalle_ctas = $listado_detalle_ctas->count();

        $listado_detalle_ctas =
            Detalle_cta::select('cuenta', 'name', 'gpo_owner_id', 'area_id', 'install_data')->where('delegacion_id', Auth::user()->delegacion_id)->orderBy('area_id')->orderBy('cuenta')->distinct()->paginate(10);

//        $detalle_cta = Detalle_cta::find(1);

        return view('ctas/inventario/show', [
            'listado_detalle_ctas' => $listado_detalle_ctas,
//            'inventario' => $detalle_cta->inventario,
            'total_detalle_ctas' => $total_detalle_ctas
        ]);
    }
}
