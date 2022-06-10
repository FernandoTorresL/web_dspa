<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Requests\CreateFileValijasRequest;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class CuentasAdminController extends Controller
{
    /*public function show_create_file_valijas()
    {
        $user = Auth::user();

        Log::info('Visitando Ctas_Admin-Leer Archivo Valijas. Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);

        return view(
            'ctas.valijas.create_file', [
            ]);
    }

    public function create_file_valijas(CreateFileValijasRequest $request)
    {
        $user = Auth::user();

        Log::info('Creando Archivo Valijas. Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);
//        dd($request->file('archivo'));
//        dd($request->file('archivo')->getPathname());
//        dd($request->file('archivo')->getClientOriginalName());
//        dd($request->file('archivo')->getFilename());
//        dd($request->input('comment'));
        if (Gate::allows('leer_a1rchivo_valijas')) {

            $archivo = $request->file('archivo');

            $file_valijas = File::create([
                'file_original_name'    => $request->file('archivo')->getClientOriginalName(),
                'file_storage_name'     => $archivo->store('files_load_valijas', 'public'),
                'comment'               => $request->input('comment'),
                'user_id'               => $request->user()->id,
            ]);

            Log::info('Guardando File_Load_Valijas ID:'.$file_valijas->id.'|name:'.$file_valijas->file_original_name.'|Usuario:' . $file_valijas->user_id );

            return redirect('ctas/admin/preview_valijas/'.$file_valijas->id)
                ->with('message', '¡Archivo almacenado!');
        }
        else {
            Log::info('Sin permiso-Leer Archivo de Valijas. Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);
            return redirect('ctas')->with('message', 'No tiene permitido Leer archivos de valijas');
        }
    }

    public function preview_valijas(File $file_valijas)
    {
        $user = Auth::user();

        Log::info('Visitando Admin Preview Valijas. Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);

    }*/

}
