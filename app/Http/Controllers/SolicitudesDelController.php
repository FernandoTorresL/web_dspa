<?php

namespace App\Http\Controllers;

use App\Solicitud;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class SolicitudesDelController extends Controller
{

    //New function to show a better and faster table with pagination
    public function view_status()
    {
        $del = Auth::user()->delegacion_id;

        Log::info('Ver status solicitudes. User: ' . Auth::user()->name . '|Del:' . $del);

        if (Gate::allows('ver_status_solicitudes')) {

            $list_sol = Solicitud::where('delegacion_id', $del)
                    ->with(['movimiento', 'rechazo', 'gpo_actual', 'gpo_nuevo', 'resultado_solicitud.rechazo_mainframe', 'lote'])
                    ->latest()
                    ->paginate(10);

            return view('ctas.solicitudes.delegacion_list', compact('list_sol'));
        }
        else {
            Log::info('Sin permiso-Consultar estatus solicitudes. Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);

            abort(403,'No tiene permitido ver este listado');
        }

    }

}
