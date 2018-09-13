<?php

namespace App\Http\Controllers;

use App\Delegacion;
use App\Group;
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

    public function createNC(CreateValijaNCRequest $request)
    {
        $user = $request->user();
        $archivo = $request->file('archivo');

        Log::info('Enviando Crear ValijaNC. Usuario:' . $user->username );

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
        Log::info('Consultando Valija #:' . $valija->id );

        return view('ctas.valijas.show', [
            'valija' => $valija
        ]);
    }
}
