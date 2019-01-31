<?php

namespace App\Http\Controllers;

use App\Solicitud;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class SolicitudesDelController extends Controller
{

    //New function to show a sortable and simple table with pagination
    public function view_status()
    {
        $del = Auth::user()->delegacion_id;

        Log::info('Ver status solicitudes. User: ' . Auth::user()->name . '|Del:' . $del);

        if (Gate::allows('ver_status_solicitudes')) {
            if (Auth::user()->delegacion_id == 9) {
                $list_sol =
                    Solicitud::sortable()
                        ->with(['movimiento', 'rechazo', 'gpo_actual', 'gpo_nuevo', 'resultado_solicitud.rechazo_mainframe', 'lote'])
                        ->where('id', '>=', 3815)
                        ->latest()
                        ->paginate(50);
            }
            else {
                $list_sol =
                    Solicitud::sortable()
                        ->with(['movimiento', 'rechazo', 'gpo_actual', 'gpo_nuevo', 'resultado_solicitud.rechazo_mainframe', 'lote'])
                        ->where('delegacion_id', $del)
                        ->where('id', '>=', 3815)
                        ->latest()
                        ->paginate(20);
            }

            return view('ctas.solicitudes.delegacion_list', compact('list_sol'));
        }
        else {
            Log::info('Sin permiso-Consultar estatus solicitudes. Usuario:' . Auth::user()->name . '|Del:' . $del);

            abort(403,'No tiene permitido ver este listado');
        }

    }

    public function view_detail_status()
    {
        $del = Auth::user()->delegacion_id;

        Log::info('Ver status solicitudes. User: ' . Auth::user()->name . '|Del:' . $del);

        if (Gate::allows('ver_detail_status_solicitudes')) {
            if (Auth::user()->delegacion_id == 9) {
                $listado_solicitudes =
                    Solicitud::sortable()
                        ->with(['movimiento', 'rechazo', 'gpo_actual', 'gpo_nuevo', 'resultado_solicitud.rechazo_mainframe', 'lote'])
                        ->where('id', '>=', 3815)
                        ->latest()
                        ->paginate(50);
            }
            else {
                $listado_solicitudes =
                    Solicitud::sortable()
                        ->with(['movimiento', 'rechazo', 'gpo_actual', 'gpo_nuevo', 'resultado_solicitud.rechazo_mainframe', 'lote'])
                        ->where('delegacion_id', $del)
                        ->where('id', '>=', 3815)
                        ->latest()
                        ->paginate(20);
            }

            return view('ctas.solicitudes.listado_status', compact('listado_solicitudes'));
        }
        else {
            Log::info('Sin permiso-Consultar estatus solicitudes. Usuario:' . Auth::user()->name . '|Del:' . $del);

            abort(403,'No tiene permitido ver este listado');
        }

    }

}
