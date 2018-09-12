<?php

namespace App\Http\Controllers;

use App\Delegacion;
use App\Group;
use App\Http\Requests\CreateSolicitudNCRequest;
use App\Http\Requests\CreateSolicitudRequest;
use App\Movimiento;
use App\Rechazo;
use App\Solicitud;
use App\Subdelegacion;
use App\Valija;
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

        $movimientos = Movimiento::where('status', '<>', 0)->orderBy('name', 'asc')->get();
        $subdelegaciones = Subdelegacion::where('delegacion_id', $del_id)->where('status', '<>', 0)->orderBy('num_sub', 'asc')->get();
        $gruposNuevo =  Group::whereBetween('status', [1, 2])->orderBy('name', 'asc')->get();
        $gruposActual = Group::whereBetween('status', [1, 3])->orderBy('name', 'asc')->get();

        Log::info('Visitando Crear Solicitud. Usuario:' . $user . '|Del:(' . $del_id . ')-' . $del_name);

        return view(
            'ctas.solicitudes.create', [
            'del_id' => $del_id,
            'del_name' => $del_name,
            'movimientos' =>  $movimientos,
            'subdelegaciones' =>  $subdelegaciones,
            'gruposNuevo' =>  $gruposNuevo,
            'gruposActual' =>  $gruposActual,
        ]);
    }

    public function homeNC()
    {
        $user = Auth::user()->name;
        $del_id = Auth::user()->delegacion_id;
        $del_name = Auth::user()->delegacion->name;

        $valijas = Valija::with('delegacion')->where('status', '<>', 0)->orderBy('num_oficio_ca', 'asc')->get();
        $movimientos = Movimiento::where('status', '<>', 0)->orderBy('name', 'asc')->get();
        $subdelegaciones = Subdelegacion::with('delegacion')->where('status', '<>', 0)->orderBy('id', 'asc')->get();

        $gruposNuevo =  Group::whereBetween('status', [1, 2])->orderBy('name', 'asc')->get();
        $gruposActual = Group::whereBetween('status', [1, 3])->orderBy('name', 'asc')->get();

        $rechazos = Rechazo::all();

        Log::info('Visitando Crear SolicitudNC. Usuario:' . $user . '|Del:(' . $del_id . ')-' . $del_name);

        return view(
            'ctas.solicitudes.createNC', [
                    'valijas' => $valijas,
                    'movimientos' =>  $movimientos,
                    'subdelegaciones' =>  $subdelegaciones,
                    'gruposNuevo' =>  $gruposNuevo,
                    'gruposActual' =>  $gruposActual,
                    'rechazos' => $rechazos,
        ]);
    }

    public function create(CreateSolicitudRequest $request)
    {
        $user = $request->user();
        $archivo = $request->file('archivo');

        Log::info('Enviando Crear Solicitud. Usuario:' . $user->username );

        if (null == $request->input('gpo_nuevo')) {
            $gpo_nuevo = 0;
        } else {
            $gpo_nuevo = $request->input('gpo_nuevo');
        }

        if (null == $request->input('gpo_actual')) {
            $gpo_actual = 0;
        } else {
            $gpo_actual = $request->input('gpo_actual');
        }

        $solicitud = Solicitud::create([
            'fecha_solicitud_del' => $request->input('fecha_solicitud'),
            'delegacion_id' => $user->delegacion_id,
            'subdelegacion_id' => $request->input('subdelegacion'),
            'nombre' => strtoupper($request->input('nombre')),
            'primer_apellido' => strtoupper($request->input('primer_apellido')),
            'segundo_apellido' => strtoupper($request->input('segundo_apellido')),
            'matricula' => $request->input('matricula'),
            'curp' => strtoupper($request->input('curp')),
            'cuenta' => strtoupper($request->input('cuenta')),
            'movimiento_id' => $request->input('tipo_movimiento'),
            'gpo_nuevo_id' => $gpo_nuevo,
            'gpo_actual_id' => $gpo_actual,
            'comment' => $request->input('comment'),
            'archivo' => $archivo->store('solicitudes/' . $user->delegacion_id, 'public'),
            'user_id' => $user->id,
        ]);

        return redirect('ctas/solicitudes/'.$solicitud->id)->with('message', '¡Solicitud creada!');
    }

    public function createNC(CreateSolicitudNCRequest $request)
    {
        $user = $request->user();
        $archivo = $request->file('archivo');

        Log::info('Enviando Crear SolicitudNC. Usuario:' . $user->username );

        $solicitud = Solicitud::create([
            'valija_id' => $request->input('valija'),
            'fecha_solicitud_del' => $request->input('fecha_solicitud'),
            'delegacion_id' => 1,
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
            'rechazo_id' => $request->input('rechazo'),
            'archivo' => $archivo->store('solicitudes/' . $user->delegacion_id, 'public'),
            'user_id' => $user->id,
        ]);

        return redirect('ctas/solicitudes/'.$solicitud->id)->with('message', '¡Solicitud creada!');
    }

    public function show(Solicitud $solicitud)
    {
        Log::info('Consultando Solicitud ID:' . $solicitud->id );
        return view('ctas.solicitudes.show', [
            'solicitud' => $solicitud
        ]);
    }
}
