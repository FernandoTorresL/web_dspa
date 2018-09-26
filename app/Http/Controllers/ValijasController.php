<?php

namespace App\Http\Controllers;

use App\Delegacion;
use App\Group;
use App\Hist_valija;
use App\Http\Requests\CreateSolicitudNCRequest;
use App\Http\Requests\CreateSolicitudRequest;
use App\Http\Requests\CreateValijaNCRequest;
use App\Movimiento;
use App\Rechazo;
use App\Solicitud;
use App\Subdelegacion;
use App\Valija;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ValijasController extends Controller
{
    public function homeNC()
    {
        $user = Auth::user()->name;
        $del_id = Auth::user()->delegacion_id;

        $delegaciones = Delegacion::where('status', 1)->orderBy('id', 'asc')->get();

        Log::info('Visitando Crear ValijaNC. Usuario:' . $user . '|Del:' . $del_id);

        return view(
            'ctas.valijas.createNC', [
                    'delegaciones' =>  $delegaciones,
        ]);
    }

    public function show_for_edit(Valija $valija)
    {
        $user = Auth::user()->name;

        $delegaciones = Delegacion::where('status', 1)->orderBy('id', 'asc')->get();

        Log::info('Editando Valija desde Nivel Central. Usuario:' . $user);

        return view(
            'ctas.valijas.editNC', [
                'val_original' => $valija,
                'delegaciones' =>  $delegaciones,
        ]);
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
        $user = $request->user();
        $archivo = $request->file('archivo');

        Log::info('Creando Valija desde Nivel Central. Usuario:' . $user->username );

        $valija = Valija::create([
            'origen_id' => $user->id,
            'status' => 1,
            'num_oficio_ca' => $request->input('num_oficio_ca'),
            'fecha_recepcion_ca' => $request->input('fecha_recepcion_ca'),
            'delegacion_id' => $request->input('delegacion'),
            'num_oficio_del' => $request->input('num_oficio_del'),
            'fecha_valija_del' => $request->input('fecha_valija_del'),
            'comment' => $request->input('comment'),
            'archivo' => $archivo->store('valijas/' . $request->input('delegacion'), 'public'),
            'user_id' => $user->id,
        ]);

        return redirect('ctas/valijas/'.$valija->id)->with('message', '¡Valija creada!');
    }

    public function show(Valija $valija)
    {

        $valija_hasBeenModified = $valija->hasBeenModified($valija);

        Log::info('Consultando Valija #:' . $valija->id );

        return view('ctas.valijas.show', [
            'valija' => $valija,
            'valija_hasBeenModified' => $valija_hasBeenModified,
        ]);
    }
}
