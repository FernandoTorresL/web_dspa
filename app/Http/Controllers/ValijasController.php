<?php

namespace App\Http\Controllers;

use App\Delegacion;
use App\Hist_valija;
use App\Http\Requests\CreateValijaNCRequest;
use App\Valija;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ValijasController extends Controller
{
    public function homeNC()
    {
        if (Gate::allows('capture_val_nc')) {
            if (Auth::user()->hasRole('capturista_dspa'))
            {
                $primer_renglon = 'Nivel Central - DSPA';
            }
            elseif (Auth::user()->hasRole('capturista_cceyvd'))
            {
                $primer_renglon = 'Nivel Central - CCEyVD';
            }

            Log::info('Visitando Crear ValijaNC. Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);
            $delegaciones = Delegacion::where('status', 1)->orderBy('id', 'asc')->get();

            return view(
                'ctas.valijas.createNC', [
                'primer_renglon'    => $primer_renglon,
                'delegaciones'      => $delegaciones,
            ]);
        }
        else {
            Log::info('Sin permiso-Crear ValijaNC. Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);

            return 'No tiene permitido crear Valijas de Nivel Central';
        }

    }

    public function show_for_edit(Valija $valija)
    {
        if (Gate::allows('editar_valijas')) {
            if (
                Auth::user()->hasRole('capturista_dspa') ||
                (Auth::user()->hasRole('capturista_cceyvd') && $valija->origen_id == 12))
            {
                $delegaciones = Delegacion::where('status', 1)->orderBy('id', 'asc')->get();

                Log::info('Editando Valija desde Nivel Central. Usuario:' . Auth::user()->name);

                return view(
                    'ctas.valijas.editNC', [
                    'val_original' => $valija,
                    'delegaciones' =>  $delegaciones,
                ]);
            }
            else
            {
                Log::info('Valija ajena-Editar Valija:'.$valija->id.'|Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);
                return redirect('ctas')->with('message', 'No tiene autorización para editar Valijas de otra Coordinación');
            }
        }
        else {
            Log::info('Sin permiso-Editar Valija:'.$valija->id.'|Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);
            return redirect('ctas')->with('message', 'No tiene permitido editar Valijas');
        }
    }

    public function editNC(CreateValijaNCRequest $request, $id)
    {
        $user = Auth::user();

        $valija_original = Valija::find($id);

        $valija_hist = Hist_valija::create([
            'valija_id'         => $valija_original->id,
            'origen_id'         => $valija_original->origen_id,
            'status'            => $valija_original->status,
            'num_oficio_ca'     => $valija_original->num_oficio_ca,
            'fecha_recepcion_ca'=> $valija_original->fecha_recepcion_ca,
            'delegacion_id'     => $valija_original->delegacion_id,
            'num_oficio_del'    => $valija_original->num_oficio_del,
            'fecha_valija_del'  => $valija_original->fecha_valija_del,
            'rechazo_id'        => $valija_original->rechazo_id,
            'comment'           => $valija_original->comment,
            'archivo'           => $valija_original->archivo,
            'user_id'           => $valija_original->user_id,
        ]);

        Log::info('Nva Valija Hist:' . $valija_hist->id . '| Usuario:' . $user->username );

        $valija = Valija::find($id);
        $archivo = $request->file('archivo');

        $valija->num_oficio_ca      = $request->input('num_oficio_ca');
        $valija->fecha_recepcion_ca = $request->input('fecha_recepcion_ca');
        $valija->delegacion_id      = $request->input('delegacion');
        $valija->num_oficio_del     = $request->input('num_oficio_del');
        $valija->fecha_valija_del   = $request->input('fecha_valija_del');
        $valija->comment            = $request->input('comment');
        $valija->archivo            = $archivo->store('valijas/' . $request->input('delegacion'), 'public');
        $valija->user_id            = $user->id;

        $valija->save();

        Log::info('Valija ' . $valija->id . ' editada. Usuario:' . $user->name);

        return redirect('ctas/valijas/' . $id)->with('message', '¡Valija editada!');
    }

    public function createNC(CreateValijaNCRequest $request)
    {
        if (Gate::allows('capture_val_nc')) {
            if (Auth::user()->hasRole('capturista_dspa'))
            {
                $origen_id = 2;
                $prefijo_gestion = 'CA-';
            }
            elseif (Auth::user()->hasRole('capturista_cceyvd'))
            {
                $origen_id = 12;
                $prefijo_gestion = 'CCEyVD-';
            }

            $archivo = $request->file('archivo');
            $valija = Valija::create([
                'origen_id'             => $origen_id,
                'status'                => 1,
                'num_oficio_ca'         => $prefijo_gestion.$request->input('num_oficio_ca'),
                'fecha_recepcion_ca'    => $request->input('fecha_recepcion_ca'),
                'delegacion_id'         => $request->input('delegacion'),
                'num_oficio_del'        => $request->input('num_oficio_del'),
                'fecha_valija_del'      => $request->input('fecha_valija_del'),
                'comment'               => $request->input('comment'),
                'archivo'               => $archivo->store('valijas/' . $request->input('delegacion'), 'public'),
                'user_id'               => $request->user()->id,
            ]);

            Log::info('Creando Valija NC ID:'.$valija->id.'|num_oficio_ca:'.$valija->num_oficio_ca.'|Usuario:' . $request->user()->username );

            return redirect('ctas/valijas/'.$valija->id)->with('message', '¡Valija creada!');
        }
        else {
            Log::info('Sin permiso-Crear ValijaNC. Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);
            return redirect('ctas')->with('message', 'No tiene permitido crear Valijas de Nivel Central');
        }

    }

    public function show(Valija $valija)
    {
        if (Gate::allows('consultar_valijas')) {
            if (
                Auth::user()->hasRole('capturista_dspa') ||
                (Auth::user()->hasRole('capturista_cceyvd') && $valija->origen_id == 12))
            {
                $valija_hasBeenModified = $valija->hasBeenModified($valija);
                Log::info('Consultando Valija:'.$valija->id.'|Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);

                return view('ctas.valijas.show', [
                    'valija' => $valija,
                    'valija_hasBeenModified' => $valija_hasBeenModified,
                ]);
            }
            else
            {
                Log::info('Valija ajena-Consultar Valija:'.$valija->id.'|Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);
                return redirect('ctas')->with('message', 'No tiene autorización para consultar esta Valija de otra Coordinación');
            }
        }
        else {
            Log::info('Sin permiso-Consultar Valija:'.$valija->id.'|Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);
            return redirect('ctas')->with('message', 'No tiene permitido consultar Valijas');
        }
    }
}
