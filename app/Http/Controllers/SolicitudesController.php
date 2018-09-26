<?php

namespace App\Http\Controllers;

use App\Group;
use App\Hist_solicitud;
use App\Http\Requests\CreateSolicitudNCRequest;
use App\Http\Requests\CreateSolicitudRequest;
use App\Movimiento;
use App\Rechazo;
use App\Solicitud;
use App\Subdelegacion;
use App\Valija;
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

        $valijas = Valija::with('delegacion')->where('status', '<>', 0)->orderBy('id', 'desc')->get();
        $movimientos = Movimiento::where('status', '<>', 0)->orderBy('name', 'asc')->get();
        $subdelegaciones = Subdelegacion::with('delegacion')->where('status', '<>', 0)->orderBy('id', 'asc')->get();

        $gruposNuevo =  Group::whereBetween('status', [1, 2])->orderBy('name', 'asc')->get();
        $gruposActual = Group::whereBetween('status', [1, 3])->orderBy('name', 'asc')->get();

        $rechazos = Rechazo::all();

        Log::info('Visitando Crear Solicitud desde Nivel Central. Usuario:' . $user . '|Del:(' . $del_id . ')-' . $del_name);

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

    public function show_for_edit(Solicitud $solicitud)
    {
        $user = Auth::user()->name;

        $valijas = Valija::with('delegacion')->where('status', '<>', 0)->orderBy('num_oficio_ca', 'asc')->get();
        $movimientos = Movimiento::where('status', '<>', 0)->orderBy('name', 'asc')->get();
        $subdelegaciones = Subdelegacion::with('delegacion')->where('status', '<>', 0)->orderBy('id', 'asc')->get();
        $gruposNuevo =  Group::whereBetween('status', [1, 2])->orderBy('name', 'asc')->get();
        $gruposActual = Group::whereBetween('status', [1, 3])->orderBy('name', 'asc')->get();
        $rechazos = Rechazo::all();

        Log::info('Editando Solicitud desde Nivel Central. Usuario:' . $user);

        return view(
            'ctas.solicitudes.editNC', [
            'sol_original' => $solicitud,
            'valijas' => $valijas,
            'movimientos' => $movimientos,
            'subdelegaciones' => $subdelegaciones,
            'gruposNuevo' => $gruposNuevo,
            'gruposActual' => $gruposActual,
            'rechazos' => $rechazos,
        ]);
    }

    public function editNC(CreateSolicitudNCRequest $request, $id)
    {
        $user = Auth::user();

        $solicitud_original = Solicitud::find($id);

        $solicitud_hist = Hist_solicitud::create([
            'solicitud_id'          => $solicitud_original->id,
            'valija_id'             => $solicitud_original->valija_id,
            'fecha_solicitud_del'   => $solicitud_original->fecha_solicitud_del,
            'lote_id'               => $solicitud_original->lote_id,
            'delegacion_id'         => $solicitud_original->delegacion_id,
            'subdelegacion_id'      => $solicitud_original->subdelegacion_id,
            'nombre'                => $solicitud_original->nombre,
            'primer_apellido'       => $solicitud_original->primer_apellido,
            'segundo_apellido'      => $solicitud_original->segundo_apellido,
            'matricula'             => $solicitud_original->matricula,
            'curp'                  => $solicitud_original->curp,
            'cuenta'                => $solicitud_original->cuenta,
            'movimiento_id'         => $solicitud_original->movimiento_id,
            'gpo_nuevo_id'          => $solicitud_original->gpo_nuevo_id,
            'gpo_actual_id'         => $solicitud_original->gpo_actual_id,
            'comment'               => $solicitud_original->comment,
            'rechazo_id'            => $solicitud_original->rechazo_id,
            'archivo'               => $solicitud_original->archivo,
            'user_id'               => $solicitud_original->user_id,
        ]);

        Log::info('Nva Solicitud Hist:' . $solicitud_hist->id . '| Usuario:' . $user->username );

        $solicitud = Solicitud::find($id);
        $delegacion = Subdelegacion::find($request->input('subdelegacion'))->delegacion->id;
        $archivo = $request->file('archivo');

        $solicitud->valija_id               = $request->input('valija');
        $solicitud->fecha_solicitud_del     = $request->input('fecha_solicitud');
        $solicitud->lote_id                 = $request->input('lote');
        $solicitud->delegacion_id           = $delegacion;
        $solicitud->subdelegacion_id        = $request->input('subdelegacion');
        $solicitud->nombre                  = strtoupper($request->input('nombre'));
        $solicitud->primer_apellido         = strtoupper($request->input('primer_apellido'));
        $solicitud->segundo_apellido        = strtoupper($request->input('segundo_apellido'));
        $solicitud->matricula               = $request->input('matricula');
        $solicitud->curp                    = strtoupper($request->input('curp'));
        $solicitud->cuenta                  = strtoupper($request->input('cuenta'));
        $solicitud->movimiento_id           = $request->input('tipo_movimiento');
        $solicitud->gpo_nuevo_id            = $request->input('gpo_nuevo');
        $solicitud->gpo_actual_id           = $request->input('gpo_actual');
        $solicitud->comment                 = $request->input('comment');
        $solicitud->rechazo_id              = $request->input('rechazo');
        $solicitud->archivo                 = $request->file('archivo')->store('solicitudes/' . $delegacion, 'public');
        $solicitud->user_id                 = $user->id;

        $solicitud->save();

        Log::info('Solicitud ' . $solicitud->id . ' editada. Usuario:' . $user->name);

        return redirect('ctas/solicitudes/' . $id)->with('message', '¡Solicitud editada!');
    }

    public function create(CreateSolicitudRequest $request)
    {
        $user = $request->user();
        $archivo = $request->file('archivo');

        Log::info('Creando Solicitud. Usuario:' . $user->username );

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
            'gpo_nuevo_id' => $request->input('gpo_nuevo'),
            'gpo_actual_id' => $request->input('gpo_actual'),
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
        $delegacion = Subdelegacion::find($request->input('subdelegacion'))->delegacion->id;

        Log::info('Creando Solicitud desde Nivel Central. Usuario:' . $user->username );

        $solicitud = Solicitud::create([
            'valija_id' => $request->input('valija'),
            'fecha_solicitud_del' => $request->input('fecha_solicitud'),
            'delegacion_id' => $delegacion,
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
            'archivo' => $archivo->store('solicitudes/' . $delegacion, 'public'),
            'user_id' => $user->id,
        ]);

        return redirect('ctas/solicitudes/'.$solicitud->id)->with('message', '¡Solicitud creada!');
    }

    public function show(Solicitud $solicitud)
    {
        $solicitud_hasBeenModified = $solicitud->hasBeenModified($solicitud);

        $solicitud->load('user');

        Log::info('Consultando Solicitud ID:' . $solicitud->id );

        return view('ctas.solicitudes.show', [
            'solicitud' => $solicitud,
            'solicitud_hasBeenModified' => $solicitud_hasBeenModified,
        ]);
    }
}
