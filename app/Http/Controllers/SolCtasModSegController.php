<?php

namespace App\Http\Controllers;

use App\SolModSeg;
use App\RoleModSeg;
use App\Subdelegacion;
use App\Rechazo;

use App\Http\Requests\CreateSolModSegRequest;
//use App\Http\Requests\EditSolicitudRequest;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class SolCtasModSegController extends Controller
{

    public function home()
    {
        $user_name = Auth::user()->name;
        $user_del_id = Auth::user()->delegacion_id;
        $user_del_name = Auth::user()->delegacion->name;

        $texto_log = '|Usuario:' . $user_name . '|Del:' . $user_del_id;

        if (Gate::allows('capture_sol_mod_seg_nc') || Gate::allows('capture_sol_mod_seg_del') ) {
            Log::info('Capturar Solicitud de Usuario para el Módulo de Seguimiento' . $texto_log);

            $roles_mod_seg = RoleModSeg::where('status', 1)->orderBy('id', 'asc')->get();

            if (Gate::allows('capture_sol_mod_seg_del')) {
                $subdelegaciones = Subdelegacion::where('delegacion_id', $user_del_id)->where('status', '<>', 0)->orderBy('num_sub', 'asc')->get();
                $rechazos = '';
            }
            elseif (Gate::allows('capture_sol_mod_seg_nc')) {
                $subdelegaciones = Subdelegacion::with('delegacion')->where('status', '<>', 0)->orderBy('id', 'asc')->get();
                $rechazos = Rechazo::all();
            }
        }
        else {
            Log::warning('Sin permiso-Capturar Solicitudes para el Módulo de Seguimiento' . $texto_log);
            return redirect('ctas_mod_seg')->with('message', 'No tiene permitido capturar solicitudes de usuario para el Módulo de Seguimiento.');
        }

        return view(
            'ctas_mod_seg.sol_mod_seg.create_cta_mod_seg', [
            'del_id' => $user_del_id,
            'del_name' => $user_del_name,
            'roles_mod_seg' => $roles_mod_seg,
            'subdelegaciones' => $subdelegaciones,
            'rechazos' => $rechazos,
        ]);
    }

    public function create_cta_mod_seg(CreateSolModSegRequest $request)
    {
        $user = $request->user();
        $archivo_ine = $request->file('archivo_ine');
        $archivo_comp_dom = $request->file('archivo_comp_dom');
        $archivo_responsiva = $request->file('archivo_responsiva');
        $texto_log = 'Usuario:' . $user->name . '|Del:' . $user->delegacion_id;

        Log::info('Creando Solicitud. ' . $texto_log);

        $solicitud_mod_seg = SolModSeg::create([
            'fecha_solicitud_del' => $request->input('fecha_solicitud'),
            'delegacion_id' => $user->delegacion_id,
            'subdelegacion_id' => $request->input('subdelegacion'),
            'nombre' => strtoupper($request->input('nombre')),
            'primer_apellido' => strtoupper($request->input('primer_apellido')),
            'segundo_apellido' => strtoupper($request->input('segundo_apellido')),
            'matricula' => $request->input('matricula'),
            'curp' => strtoupper($request->input('curp')),
            'usuario_mod_seg' => strtoupper($request->input('usuario_mod_seg')),
            'email' => strtolower($request->input('email')),
            'rol_id' => $request->input('rol'),
            'comment' => $request->input('comment'),
            'status_sol_id' => 1, //En revisión DSPA
            'rechazo_id' => $request->input('rechazo'),
            'archivo_ine' => $archivo_ine->store('sol_mod_seg/' . $user->delegacion_id, 'public'),
            'archivo_comp_dom' => $archivo_comp_dom->store('sol_mod_seg/' . $user->delegacion_id, 'public'),
            'archivo_responsiva' => $archivo_responsiva->store('sol_mod_seg/' . $user->delegacion_id, 'public'),
            'user_id' => $user->id,
        ]);

        return redirect('ctas_mod_seg/sol_mod_seg/' . $solicitud_mod_seg->id)->with('message', '¡Solicitud para ' . $solicitud_mod_seg->usuario_mod_seg . ' creada exitosamente!');
    }

        public function show_for_edit(Solicitud $solicitud)
    {
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_del_id = Auth::user()->delegacion_id;
        $texto_log = '|ID:' . $solicitud->id . '|User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id;
        $bolEditar = false;
        $estatus_solicitud = $solicitud->status_sol_id;

        if  ( Auth::user()->hasRole('admin_dspa')               && in_array($estatus_solicitud, [1, 2, 3, 4, 5]) )
            $bolEditar = true;

        if  ( Auth::user()->hasRole('admin_dspa')               && in_array($estatus_solicitud, [1, 2, 3, 4, 5]) )
            $bolEditar = true;

        if  ( (Auth::user()->hasRole('capturista_cceyvd') || Auth::user()->hasRole('autorizador_cceyvd')) && in_array( $estatus_solicitud, [1, 4]) )
            $bolEditar = true;

        if  ( Auth::user()->hasRole('capturista_delegacional')  && in_array($estatus_solicitud, [1, 2]) )
            $bolEditar = true;

        //if( ( !isset($solicitud->lote_id) && (!isset($solicitud->rechazo) && !isset($solicitud->resultado_solicitud->rechazo_mainframe)) || $user_id == 1 ) )
        if ( $bolEditar )
        {
            //Get the common information
            $roles_mod_seg = RoleModSeg::where('status', '<>', 0)->orderBy('name', 'asc')->get();
            //$gruposNuevo = Group::whereBetween('status', [1, 2])->orderBy('name', 'asc')->get();
            //$gruposActual = Group::whereBetween('status', [1, 3])->orderBy('name', 'asc')->get();
            $rechazos = Rechazo::all();

            //Get the particular information
            if (Gate::allows('editar_sol_mod_seg_user_nc')) {
                Log::info('Editando Solicitud NC' . $texto_log);
                $valijas = Valija::with('delegacion')
                    ->where('status', '<>', 0)
                    ->orderBy('num_oficio_ca', 'desc')->get();
                $subdelegaciones = Subdelegacion::with('delegacion')
                    ->where('status', '<>', 0)
                    ->orderBy('id', 'asc')->get();
            } elseif (Gate::allows('editar_sol_mod_seg_del')) {
                Log::info('Editando Solicitud Del' . $texto_log);
                $valijas = '';
                $subdelegaciones = Subdelegacion::where('delegacion_id', Auth::user()->delegacion_id)
                    ->where('status', '<>', 0)
                    ->orderBy('num_sub', 'asc')->get();
            }
        }
        else
        {
        Log::warning('Sin condiciones para editar Solicitud' . $texto_log);
        return redirect('ctas_mod_seg')->with('message', 'La solicitud ya no se puede editar.');
        }

        return view(
            'ctas_mod_seg.sol_mod_seg.edit', [
            'sol_original' => $solicitud,
            'valijas' => $valijas,
            'movimientos' => $movimientos,
            'subdelegaciones' => $subdelegaciones,
            'gruposNuevo' => $gruposNuevo,
            'gruposActual' => $gruposActual,
            'rechazos' => $rechazos,
        ]);
    }

    public function edit(EditSolicitudRequest $request, $id)
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
            'status_sol_id'         => $solicitud_original->status_sol_id,
            'comment'               => $solicitud_original->comment,
            'rechazo_id'            => $solicitud_original->rechazo_id,
            'archivo'               => $solicitud_original->archivo,
            'user_id'               => $solicitud_original->user_id,
        ]);

        Log::info('Nva Solicitud Hist. Del:' . $solicitud_hist->id . $texto_log);

        $solicitud = Solicitud::find($id);
        $delegacion = Subdelegacion::find($request->input('subdelegacion'))->delegacion->id;
        $archivo = $request->file('archivo');

        if ($request->hasfile('archivo')) {
            $nuevo_archivo = $request->file('archivo')->store('sol_mod_seg/' . $delegacion, 'public');
        }
        else
        {
            $nuevo_archivo = $solicitud_original->archivo;
        }

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
        $solicitud->archivo                 = $nuevo_archivo;
        $solicitud->user_id                 = $user_id;

        $solicitud->save();

        Log::info('Solicitud editada Del|ID:' . $solicitud->id . $texto_log);

        return redirect('ctas_mod_seg/sol_mod_seg/' . $id)->with('message', '¡Solicitud editada!');
    }
}
