<?php

namespace App\Http\Controllers;

use App\Lote;
use App\Http\Requests\CreateLoteRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class CuentasAdminCrearLoteController extends Controller
{
    public function captura_lote()
    {
        $user = Auth::user();

        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_delegacion_id = Auth::user()->delegacion_id;

        $texto_log = '|User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user->delegacion->id;

        if ( Auth::user()->hasRole('admin_dspa') && Gate::allows('crear_lote') ) {

            Log::info('Capturar Lote' . $texto_log);

            //Obtener los últimos lotes creados
            $lista_ultimos_lotes = DB::table('lotes')
                ->leftjoin('resultado_lotes', 'lotes.id', '=', 'resultado_lotes.lote_id')
                ->leftjoin('solicitudes', 'lotes.id', '=', 'solicitudes.lote_id')
                ->select( 'lotes.id', 'lotes.num_lote', 'lotes.num_oficio_ca', 'lotes.fecha_oficio_lote', 'lotes.ticket_msi', 'lotes.comment', 'resultado_lotes.attended_at', 'lotes.created_at', 'lotes.updated_at',
                    DB::raw('COUNT(solicitudes.id) as total_solicitudes') )
                ->groupBy('lotes.id', 'lotes.num_lote', 'lotes.num_oficio_ca', 'lotes.fecha_oficio_lote', 'lotes.ticket_msi', 'lotes.comment', 'resultado_lotes.attended_at', 'lotes.created_at', 'lotes.updated_at')
                ->orderBy( 'lotes.id', 'desc')->limit(5)->get();

            return view(
                'ctas.lotes.captura_lote', [
                    'lista_ultimos_lotes'      => $lista_ultimos_lotes,
                ]);
        }
        else {
            Log::info('Sin permiso-Crear Lote' . $texto_log);

            abort(403,'No tiene permitido crear lotes');
        }
    }

    public function crear_lote(CreateLoteRequest $request)
    {
        if ( Auth::user()->hasRole('admin_dspa') && Gate::allows('crear_lote') )
        {
            $lote = Lote::create([
                'num_lote' => $request->input('num_lote'),
                'num_oficio_ca' => '',
                'fecha_oficio_lote' => date("Y/m/d"),
                //'ticket_msi' => null,
                'status' => 1,
                'comment' => $request->input('comment'),
                //'archivo' => null,
                'user_id' => $request->user()->id,
            ]);

            Log::info('Creando Lote:'.$lote->id.'|Usuario:' . $request->user()->username );

            return redirect('/ctas/admin/captura_lote')->with('message', '¡Lote Creado!');
            //return '¡Lote Creado!';
        }
        else {
            Log::info('Sin permiso-Crear Lote. Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);
            return redirect('ctas')->with('message', 'No tiene permitido crear Lotes de Nivel Central');
        }
    }
}
