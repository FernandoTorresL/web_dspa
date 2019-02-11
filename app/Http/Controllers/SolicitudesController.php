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
use Illuminate\Support\Facades\Gate;

class SolicitudesController extends Controller
{
    public function home()
    {

        if (Gate::allows('capture_sol_del')) {

            Log::info('Capturar Solicitud-Delegación. Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);

            $movimientos = Movimiento::where('status', '<>', 0)->orderBy('name', 'asc')->get();
            $subdelegaciones = Subdelegacion::where('delegacion_id', Auth::user()->delegacion_id)->where('status', '<>', 0)->orderBy('num_sub', 'asc')->get();
            $gruposNuevo =  Group::whereBetween('status', [1, 2])->orderBy('name', 'asc')->get();
            $gruposActual = Group::whereBetween('status', [1, 3])->orderBy('name', 'asc')->get();

            Log::info('Vista Capturar Solicitud-Delegación. Usuario:' . Auth::user()->name . '|Del:(' . Auth::user()->delegacion_id . ')-' . Auth::user()->delegacion->name);

            return view(
                'ctas.solicitudes.create', [
                'del_id' => Auth::user()->delegacion_id,
                'del_name' => Auth::user()->delegacion->name,
                'movimientos' => $movimientos,
                'subdelegaciones' => $subdelegaciones,
                'gruposNuevo' => $gruposNuevo,
                'gruposActual' => $gruposActual,
            ]);
        }
        else {
            Log::warning('Sin permiso-Capturar Solicitud. Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);

            return "No estas autorizado a ver esta página";
        }
    }

    public function homeNC()
    {
        $user = Auth::user()->name;
        $del_id = Auth::user()->delegacion_id;
        $del_name = Auth::user()->delegacion->name;

        $valijas = Valija::with('delegacion')->where('status', '<>', 0)->orderBy('num_oficio_ca', 'desc')->get();
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

        Log::info('Ver Solicitud-Delegación. Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);

        if (Gate::allows('consultar_solicitudes_del')) {

            $movimientos = Movimiento::where('status', '<>', 0)->orderBy('name', 'asc')->get();
            $subdelegaciones = Subdelegacion::where('delegacion_id', Auth::user()->delegacion_id)->where('status', '<>', 0)->orderBy('num_sub', 'asc')->get();
            $gruposNuevo =  Group::whereBetween('status', [1, 2])->orderBy('name', 'asc')->get();
            $gruposActual = Group::whereBetween('status', [1, 3])->orderBy('name', 'asc')->get();
            $rechazos = Rechazo::all();

            Log::info('Ver Solicitud-Delegación: '.$solicitud->id.' Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);

            return view(
                'ctas.solicitudes.edit', [
                'sol_original' => $solicitud,
                'movimientos' => $movimientos,
                'subdelegaciones' => $subdelegaciones,
                'gruposNuevo' => $gruposNuevo,
                'gruposActual' => $gruposActual,
                'rechazos' => $rechazos,
            ]);
        }
        else {
            Log::warning('Sin permiso-Ver Solicitud. Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);

            abort(403,'No tiene permitido ver ésta solicitud');
        }

    }

    public function show_for_editNC(Solicitud $solicitud)
    {
        $user = Auth::user()->name;

        $valijas = Valija::with('delegacion')
//            ->where('created_at', '>', '2018-10-16')
            ->orderBy('num_oficio_ca', 'desc')->get();
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

    public function edit(CreateSolicitudRequest $request, $id)
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

//        $solicitud->valija_id               = $request->input('valija');
        $solicitud->fecha_solicitud_del     = $request->input('fecha_solicitud');
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

        if ($request->input('valija') <> 0) {
            $solicitud->valija_id           = $request->input('valija');
        }

        $solicitud->fecha_solicitud_del     = $request->input('fecha_solicitud');
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

        Log::info('Creando Solicitud-Delegación. Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);

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

        Log::info('Consultando Solicitud ID:' . $solicitud->id . ' Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);

        if (Auth::user()->delegacion_id == 9 || Auth::user()->delegacion_id == $solicitud->delegacion_id) {
            return view('ctas.solicitudes.show', [
                'solicitud' => $solicitud,
                'solicitud_hasBeenModified' => $solicitud_hasBeenModified,
            ]);
        }
        else {
            Log::warning('Sin permiso-Consultar solicitudes de otra del. Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);

            abort(403,'No tiene permitido ver solicitudes de otra delegación');
        }
    }

    public function solicitudes_status()
    {
        Log::info('Ver status solicitudes. Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);

        if (Gate::allows('ver_status_solicitudes')) {
            if (Auth::user()->delegacion_id == 9) {
                $listado_solicitudes = Solicitud::select('id', 'lote_id', 'valija_id',
                    'archivo', 'created_at', 'updated_at', 'delegacion_id', 'subdelegacion_id',
                    'cuenta', 'nombre', 'primer_apellido', 'segundo_apellido', 'movimiento_id',
                    'rechazo_id', 'comment', 'user_id', 'gpo_actual_id', 'gpo_nuevo_id')
                    ->with(['user', 'valija', 'delegacion', 'subdelegacion', 'movimiento',
                        'rechazo', 'gpo_actual', 'gpo_nuevo', 'resultado_solicitud', 'lote'])
                    ->orderby('created_at', 'desc')->limit(200)->get();
            }
            else {
                $listado_solicitudes = Solicitud::select('id', 'lote_id', 'valija_id',
                    'archivo', 'created_at', 'updated_at', 'delegacion_id', 'subdelegacion_id',
                    'cuenta', 'nombre', 'primer_apellido', 'segundo_apellido', 'movimiento_id',
                    'rechazo_id', 'comment', 'user_id', 'gpo_actual_id', 'gpo_nuevo_id')
                    ->with(['user', 'valija', 'delegacion', 'subdelegacion', 'movimiento',
                        'rechazo', 'gpo_actual', 'gpo_nuevo', 'resultado_solicitud', 'lote'])
                    ->where('delegacion_id', Auth::user()->delegacion_id)
                    ->orderby('created_at', 'desc')->limit(100)->get();
    //                ->paginate(10);
            }
//            dd($listado_solicitudes[1]);
            return view(
                'ctas.solicitudes.listado_status', [
                'listado_solicitudes' => $listado_solicitudes,
            ]);
        }
        else {
            Log::info('Sin permiso-Consultar estatus solicitudes. Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);

            abort(403,'No tiene permitido ver este listado');
        }

    }

}
