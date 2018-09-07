<?php

namespace App\Http\Controllers;

use App\Detalle_cta;
use App\User;
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

        Log::info('Visitando Inventario. Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id );

        $listado_detalle_ctas =
            Detalle_cta::select('cuenta', 'name', 'gpo_owner_id', 'install_data', 'work_area_id')->with('gpo_owner', 'work_area')->distinct()->where('delegacion_id', Auth::user()->delegacion_id)->orderBy('work_area_id', 'desc')->get();

        $detalle_cta = Detalle_cta::find(1);

        return view('ctas/inventario/show', [
            'listado_detalle_ctas' => $listado_detalle_ctas,
            'inventory' => $detalle_cta->inventory,
        ]);
    }
}
