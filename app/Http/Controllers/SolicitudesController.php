<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSolicitudRequest;
use App\Solicitud;
use App\Subdelegacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SolicitudesController extends Controller
{
    public function home()
    {
        $user = Auth::user()->name;
        $del_id = Auth::user()->delegacion_id;
        $del_name = Auth::user()->delegacion->name;

        $subdelegaciones = Subdelegacion::where('delegacion_id', $del_id)->orderBy('num_sub', 'name')->get();

        Log::info('Visitando Crear Solicitud. Usuario:' . $user . '|Del:(' . $del_id . ')-' . $del_name);

        return view(
            'ctas.solicitudes.create', [
            'subdelegaciones' =>  $subdelegaciones,
        ]);
    }

    public function create(CreateSolicitudRequest $request)
    {
        $user = Auth::user()->name;
        $user_id = Auth::user()->id;
        $del_id = Auth::user()->delegacion_id;
        $del_name = Auth::user()->delegacion->name;

        $archivo = $request->file('archivo');

        Log::info('Enviando Crear Solicitud. Usuario:' . $user . '|Del:(' . $del_id . ')-' . $del_name);

        $solicitud = Solicitud::create([
            'valija_id' => 0,
            'fecha_solicitud_del' => $request->input('fecha_solicitud'),
            'lote_id' => 0,
            'delegacion_id' => $del_id,
            'subdelegacion_id' => $request->input('subdelegacion'),
            'nombre' => strtoupper($request->input('nombre')),
            'primer_apellido' => strtoupper($request->input('primer_apellido')),
            'segundo_apellido' => strtoupper($request->input('segundo_apellido')),
            'matricula' => $request->input('matricula'),
            'curp' => strtoupper($request->input('curp')),
            'cuenta' => strtoupper($request->input('cuenta')),
            'movimiento_id' => $request->input('tipo_movimiento'),
            'gpo_nuevo_id' => $request->input('gpo_nuevo'),
            'gpo_actual_id' => $request->input('gpo_actual'),
            'comment' => $request->input('comment'),
            'causa_rechazo_id' => 0,
            'archivo' => $archivo->store('solicitudes/' . $del_id, 'public'),
            'user_id' => $user_id,
        ]);

        return 'Llegó!';
    }
}
