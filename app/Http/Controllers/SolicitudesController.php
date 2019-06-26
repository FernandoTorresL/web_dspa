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
    private function fntCheckGroupCCEVyD(Solicitud $solicitud)
    {
        $groups_ccevyd = array(env('CCEVYD_GROUP_01'), env('CCEVYD_GROUP_02'), env('CCEVYD_GROUP_03'),
            env('CCEVYD_GROUP_04'), env('CCEVYD_GROUP_05'), env('CCEVYD_GROUP_06'),
            env('CCEVYD_GROUP_07'));

        //If 'solicitud' has value on gpo_nuevo...
        if (isset($solicitud->gpo_nuevo))
            if (in_array($solicitud->gpo_nuevo->name, $groups_ccevyd))
                return true;

        //If 'solicitud' has value on gpo_actual...
        if (isset($solicitud->gpo_actual))
            if (in_array($solicitud->gpo_actual->name, $groups_ccevyd))
                return true;

        return false;
    }

    public function home()
    {
        $user_name = Auth::user()->name;
        $user_del_id = Auth::user()->delegacion_id;
        $user_del_name = Auth::user()->delegacion->name;

        $texto_log = '|Usuario:' . $user_name . '|Del:' . $user_del_id;

        if (Gate::allows('capture_sol_nc') || Gate::allows('capture_sol_del') ) {
            Log::info('Capturar Solicitud' . $texto_log);

            //Get the common information
            $movimientos = Movimiento::where('status', '<>', 0)->orderBy('name', 'asc')->get();
            $gruposNuevo =  Group::whereBetween('status', [1, 2])->orderBy('name', 'asc')->get();
            $gruposActual = Group::whereBetween('status', [1, 3])->orderBy('name', 'asc')->get();

            //Get the particular information
            if (Gate::allows('capture_sol_del')) {
                $valijas = '';
                $subdelegaciones = Subdelegacion::where('delegacion_id', $user_del_id)->where('status', '<>', 0)->orderBy('num_sub', 'asc')->get();
                $rechazos = '';
            }
            elseif (Gate::allows('capture_sol_nc')) {
                $valijas = Valija::with('delegacion')->where('status', '<>', 0)->orderBy('num_oficio_ca', 'desc')->get();
                $subdelegaciones = Subdelegacion::with('delegacion')->where('status', '<>', 0)->orderBy('id', 'asc')->get();
                $rechazos = Rechazo::all();
            }
        }
        else {
            Log::warning('Sin permiso-Capturar Solicitud' . $texto_log);
            return redirect('ctas')->with('message', 'No tiene permitido capturar solicitudes.');
        }

        return view(
            'ctas.solicitudes.create', [
            'del_id' => $user_del_id,
            'del_name' => $user_del_name,
            'valijas' => $valijas,
            'movimientos' => $movimientos,
            'subdelegaciones' => $subdelegaciones,
            'gruposNuevo' => $gruposNuevo,
            'gruposActual' => $gruposActual,
            'rechazos' => $rechazos,
        ]);
    }

    public function show(Solicitud $solicitud)
    {
        $solicitud_hasBeenModified = $solicitud->hasBeenModified($solicitud);

        $solicitud->load('user');

        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_job_id = Auth::user()->job_id;
        $user_del_id = Auth::user()->delegacion_id;

        $texto_log = ' ID:' . $solicitud->id . '|User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id;

        $allowToShowSolicitudes = false;
        $is_ccevyd_user = false;
        //If doesn't had any of the two permissions...
        if ( !( Gate::allows('consultar_solicitudes_del') || Gate::allows('consultar_solicitudes_nc') ) )
        {
            Log::warning('Sin permisos-Consultar solicitudes' . $texto_log);
            return redirect('ctas')->with('message', 'No tiene permitido consultar solicitudes.');
        }

        //If user's job is from CA, can show all 'solicitudes'
        if ( $user_job_id == env('DSPA_USER_JOB_ID_CA') && Gate::allows('consultar_solicitudes_nc') )
            $allowToShowSolicitudes = true;
        elseif ($user_job_id == env('DSPA_USER_JOB_ID_CCEVyD')) {
            //If user's job is from CCEVyD, can show 'solicitudes' from some groups
            $is_ccevyd_user = true;

            if ( ($this->fntCheckGroupCCEVyD($solicitud) ) ||
                ( $solicitud->user->id == $user_id ) ||
                ( $solicitud->user->job->id == env('DSPA_USER_JOB_ID_CCEVyD') ) )

                    $allowToShowSolicitudes = true;
            else {
                $texto_log = $texto_log . '|CCEVyD user:' . $is_ccevyd_user;
                Log::warning('Sin permiso-Consultar solicitudes de otros grupos' . $texto_log);
                return redirect('ctas')->with('message', 'No tiene permitido consultar solicitudes de otra Coordinación.');
            }
        }
        else {
            if ( ( $user_del_id == $solicitud->delegacion_id ) && Gate::allows('consultar_solicitudes_del') ) {
                //If user's job is not from CA or CCEVyD, only can show 'solicitudes' from his delegation
                $allowToShowSolicitudes = true;
                $texto_log = $texto_log . '|Del user';
            }
            else {
                Log::warning('Sin permiso-Consultar solicitudes de otra Delegación o grupos' . $texto_log);
                return redirect('ctas')->with('message', 'No tiene permitido consultar solicitudes de otra Delegación.');
            }
        }

        //All OK. Return values to the view
        $texto_log = $texto_log . '|CCEVyD user:' . $is_ccevyd_user;
        if ($allowToShowSolicitudes) {
            Log::info('Consultando solicitud' . $texto_log);

            return view('ctas.solicitudes.show', [
                'solicitud' => $solicitud,
                'solicitud_hasBeenModified' => $solicitud_hasBeenModified,
            ]);
        }

        //If reach this point, it doesn´t had the permissions
        Log::warning('Sin permiso para consultar ninguna solicitud' . $texto_log);
        return redirect('ctas')->with('message', 'No tiene permitido consultar ninguna solicitud.');
    }

    public function create(CreateSolicitudRequest $request)
    {
        $user = $request->user();
        $archivo = $request->file('archivo');
        $texto_log = 'Usuario:' . $user->name . '|Del:' . $user->delegacion_id;

        Log::info('Creando Solicitud. ' . $texto_log);

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
            'rechazo_id' => $request->input('rechazo'),
            'archivo' => $archivo->store('solicitudes/' . $user->delegacion_id, 'public'),
            'user_id' => $user->id,
        ]);

        return redirect('ctas/solicitudes/' . $solicitud->id)->with('message', '¡Solicitud para ' . $solicitud->cuenta . ' creada exitosamente!');
    }

    public function createNC(CreateSolicitudNCRequest $request)
    {
        $user = $request->user();
        $archivo = $request->file('archivo');
        $del_id = Subdelegacion::find($request->input('subdelegacion'))->delegacion->id;
        $texto_log = 'Usuario:' . $user->name . '|Del:' . $user->delegacion_id;

        Log::info('Creando Solicitud desde Nivel Central. ' . $texto_log);

        $solicitud = Solicitud::create([
            'valija_id' => $request->input('valija'),
            'fecha_solicitud_del' => $request->input('fecha_solicitud'),
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
            'rechazo_id' => $request->input('rechazo'),
            'final_remark' => $request->input('final_remark'),
            'archivo' => $archivo->store('solicitudes/' . $del_id, 'public'),
            'user_id' => $user->id,
        ]);

        return redirect('ctas/solicitudes/'.$solicitud->id)->with('message', '¡Solicitud para ' . $solicitud->cuenta . ' creada exitosamente!');
    }

    public function show_for_edit(Solicitud $solicitud)
    {
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_del_id = Auth::user()->delegacion_id;
        $texto_log = '|ID:' . $solicitud->id . '|User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id;

        if( ( !isset($solicitud->lote_id) && (!isset($solicitud->rechazo) && !isset($solicitud->resultado_solicitud->rechazo_mainframe)) || $user_id == 1 ) )
            if ((Gate::allows('editar_solicitudes_user_nc') || Gate::allows('editar_solicitudes_del'))) {
                //Get the common information
                $movimientos = Movimiento::where('status', '<>', 0)->orderBy('name', 'asc')->get();
                $gruposNuevo = Group::whereBetween('status', [1, 2])->orderBy('name', 'asc')->get();
                $gruposActual = Group::whereBetween('status', [1, 3])->orderBy('name', 'asc')->get();
                $rechazos = Rechazo::all();

                //Get the particular information
                if (Gate::allows('editar_solicitudes_user_nc')) {
                    Log::info('Editando Solicitud NC' . $texto_log);
                    $valijas = Valija::with('delegacion')
                        ->orderBy('num_oficio_ca', 'desc')->get();
                    $subdelegaciones = Subdelegacion::with('delegacion')
                        ->where('status', '<>', 0)
                        ->orderBy('id', 'asc')->get();
                } elseif (Gate::allows('editar_solicitudes_del')) {
                    Log::info('Editando Solicitud Del' . $texto_log);
                    $valijas = '';
                    $subdelegaciones = Subdelegacion::where('delegacion_id', Auth::user()->delegacion_id)
                        ->where('status', '<>', 0)
                        ->orderBy('num_sub', 'asc')->get();
                }
            } else {
                Log::warning('Sin permiso-Editar Solicitud' . $texto_log);
                return redirect('ctas')->with('message', 'No tiene permitido editar solicitudes.');
            }
        else {
            Log::warning('Sin condiciones para editar Solicitud' . $texto_log);
            return redirect('ctas')->with('message', 'La solicitud ya no se puede editar.');
        }

        return view(
            'ctas.solicitudes.edit', [
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
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_del_id = Auth::user()->delegacion_id;
        $texto_log = '|User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id;

        Log::info('Editando Solicitud Del|ID:' . $id . $texto_log);

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

        Log::info('Nva Solicitud Hist. Del:' . $solicitud_hist->id . $texto_log);

        $solicitud = Solicitud::find($id);
        $delegacion = Subdelegacion::find($request->input('subdelegacion'))->delegacion->id;
        $archivo = $request->file('archivo');

        $solicitud->fecha_solicitud_del     = $request->input('fecha_solicitud');
        $solicitud->delegacion_id           = $delegacion;
        $solicitud->subdelegacion_id        = $request->input('subdelegacion');
        $solicitud->nombre                  = strtoupper($request->input('nombre'));
        $solicitud->primer_apellido         = strtoupper($request->input('primer_apellido'));
        $solicitud->segundo_apellido        = strtoupper($request->input('segundo_apellido'));
        $solicitud->matricula               = strtoupper($request->input('matricula'));
        $solicitud->curp                    = strtoupper($request->input('curp'));
        $solicitud->cuenta                  = strtoupper($request->input('cuenta'));
        $solicitud->movimiento_id           = $request->input('tipo_movimiento');
        $solicitud->gpo_nuevo_id            = $request->input('gpo_nuevo');
        $solicitud->gpo_actual_id           = $request->input('gpo_actual');
        $solicitud->comment                 = $request->input('comment');
        $solicitud->rechazo_id              = $request->input('rechazo');
        $solicitud->archivo                 = $request->file('archivo')->store('solicitudes/' . $delegacion, 'public');
        $solicitud->user_id                 = $user_id;

        $solicitud->save();

        Log::info('Solicitud editada Del|ID:' . $solicitud->id . $texto_log);

        return redirect('ctas/solicitudes/' . $id)->with('message', '¡Solicitud editada!');
    }

    public function editNC(CreateSolicitudNCRequest $request, $id)
    {
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_del_id = Auth::user()->delegacion_id;
        $texto_log = '|User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id;

        Log::info('Editando Solicitud Nivel Central' . '|ID:' . $id . $texto_log);

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
            'final_remark'          => $solicitud_original->final_remark,
            'archivo'               => $solicitud_original->archivo,
            'user_id'               => $solicitud_original->user_id,
        ]);

        Log::info('Nva Solicitud Hist. Nivel Central:' . $solicitud_hist->id . $texto_log);

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
        $solicitud->final_remark            = $request->input('final_remark');
        $solicitud->archivo                 = $request->file('archivo')->store('solicitudes/' . $delegacion, 'public');
        $solicitud->user_id                 = $user_id;

        $solicitud->save();

        Log::info('Solicitud editada Nivel Central|ID:' . $solicitud->id . $texto_log);

        return redirect('ctas/solicitudes/' . $id)->with('message', '¡Solicitud editada!');
    }

}
