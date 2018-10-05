<?php

namespace App\Http\Controllers;

use App\Lote;
use App\Solicitud;
use App\Subdelegacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class CuentasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        $user = Auth::user();

        Log::info('Visitando Ctas-Home. Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);

        if ($user->hasRole('capturista_dspa')) {
            $primer_renglon = 'Nivel Central - DSPA';
            return view('ctas.home_ctas', [
                'primer_renglon'    => $primer_renglon,
            ]);
        }
        elseif ($user->hasRole('capturista_cceyvd'))
        {
            $primer_renglon = 'Nivel Central - CCEyVD';
            return view('ctas.home_ctas', [
                'primer_renglon'    => $primer_renglon,
            ]);
        }
        elseif ($user->hasRole('capturista_delegacional'))
        {
            $del_id = Auth::user()->delegacion_id;

            $primer_renglon = 'Delegación ' . Auth::user()->delegacion_id .'-' . Auth::user()->delegacion->name;
            $subdelegaciones = Subdelegacion::where('delegacion_id', $del_id)->where('status', '<>', 0)->orderBy('num_sub', 'asc')->get();

            $total_ctas =
                DB::table('detalle_ctas')->select('cuenta', 'gpo_owner_id', 'work_area_id')->where('delegacion_id', $del_id)->distinct()->get();

            $total_ctas_genericas =
                DB::table('detalle_ctas')->select('cuenta')->where('delegacion_id', $del_id)->where('work_area_id', 2)->distinct()->get()->count();

            $total_ctas_clas =
                DB::table('detalle_ctas')->select('cuenta')->where('delegacion_id', $del_id)->where('work_area_id', 4)->distinct()->get()->count();

            $total_ctas_fisca =
                DB::table('detalle_ctas')->select('cuenta')->where('delegacion_id', $del_id)->where('work_area_id', 46)->distinct()->get()->count();

            $total_ctas_svc =
                DB::table('detalle_ctas')->select('cuenta')->where('delegacion_id', $del_id)->where('work_area_id', 6)->distinct()->get()->count();

            $total_ctas_SSJSAV =
                DB::table('detalle_ctas')->select('cuenta')->where('delegacion_id', $del_id)->where('gpo_owner_id', 1)->distinct()->get()->count();

            $total_ctas_SSJDAV =
                DB::table('detalle_ctas')->select('cuenta')->
                where('delegacion_id', $del_id)->where('gpo_owner_id', 2)->distinct()->get()->count();

            $total_ctas_SSJOFA =
                DB::table('detalle_ctas')->select('cuenta')->
                where('delegacion_id', $del_id)->where('gpo_owner_id', 3)->distinct()->get()->count();

            $total_ctas_SSCONS =
                DB::table('detalle_ctas')->select('cuenta')->
                where('delegacion_id', $del_id)->where('gpo_owner_id', 7)->distinct()->get()->count();

            $total_ctas_SSCONX =
                DB::table('detalle_ctas')->select('cuenta')->
                where('delegacion_id', $del_id)->where('gpo_owner_id', 85)->distinct()->get()->count();

            $total_ctas_SSADIF =
                DB::table('detalle_ctas')->select('cuenta')->
                where('delegacion_id', $del_id)->where('gpo_owner_id', 12)->distinct()->get()->count();

            $total_ctas_SSOPER =
                DB::table('detalle_ctas')->select('cuenta')->
                where('delegacion_id', $del_id)->where('gpo_owner_id', 6)->distinct()->get()->count();

        return view('ctas.home_ctas', [
                'primer_renglon'    => $primer_renglon,
                'subdelegaciones' => $subdelegaciones,
                'total_ctas' => $total_ctas,
                'total_ctas_genericas' => $total_ctas_genericas,
                'total_ctas_clas' => $total_ctas_clas,
                'total_ctas_fisca' => $total_ctas_fisca,
                'total_ctas_svc' => $total_ctas_svc,
                'total_ctas_SSJSAV' => $total_ctas_SSJSAV,
                'total_ctas_SSJDAV' => $total_ctas_SSJDAV,
                'total_ctas_SSJOFA' => $total_ctas_SSJOFA,
                'total_ctas_SSCONS' => $total_ctas_SSCONS + $total_ctas_SSCONX,
                'total_ctas_SSADIF' => $total_ctas_SSADIF,
                'total_ctas_SSOPER' => $total_ctas_SSOPER,
            ]);
        }
        else return "No estas autorizado a ver esta página";
    }

    public function show_resume() {
        if (Gate::allows('ver_resumen_admin_ctas')) {
            if (Auth::user()->hasRole('capturista_dspa'))
            {
                $primer_renglon = 'Nivel Central - DSPA';
            }

            Log::info('Visitando Ver Resumen-Admin. Usuario id:' . Auth::id() . '|Nombre:|Del:' . Auth::user()->delegacion_id);

            $solicitudes_sin_lote = DB::table('solicitudes')
                ->leftjoin('valijas', 'solicitudes.valija_id', '=', 'valijas.id')
                ->join('movimientos', 'solicitudes.movimiento_id', '=', 'movimientos.id')
                ->select('valijas.origen_id', 'solicitudes.delegacion_id', 'movimientos.name', DB::raw('COUNT(valijas.origen_id) as total_solicitudes'))
                ->where('solicitudes.lote_id','=', NULL)
                ->groupBy('valijas.origen_id', 'solicitudes.delegacion_id', 'movimientos.name')
                ->orderBy('origen_id')->orderBy('name')->orderBy('delegacion_id')->get();


            $listado_lotes = DB::table('lotes')
                                ->leftjoin('resultado_lotes', 'lotes.id', '=', 'resultado_lotes.lote_id')
                                ->join('solicitudes', 'lotes.id', '=', 'solicitudes.lote_id')
                                ->select('lotes.num_lote', 'lotes.num_oficio_ca', 'lotes.fecha_oficio_lote', 'lotes.ticket_msi', 'lotes.comment', 'resultado_lotes.attended_at', DB::raw('COUNT(solicitudes.id) as total_solicitudes'))
                                ->groupBy('lotes.num_lote', 'lotes.num_oficio_ca', 'lotes.fecha_oficio_lote', 'lotes.ticket_msi', 'lotes.comment', 'resultado_lotes.attended_at')
                                ->orderBy('lotes.id', 'desc')->limit(10)->get();

            $solicitudes_sin_lote2 = Solicitud::select('id', 'lote_id', 'valija_id', 'archivo', 'created_at', 'updated_at', 'delegacion_id', 'subdelegacion_id',
                'cuenta', 'nombre', 'primer_apellido', 'segundo_apellido', 'movimiento_id', 'rechazo_id', 'comment', 'user_id', 'gpo_actual_id', 'gpo_nuevo_id')
                ->with('user', 'valija', 'delegacion', 'subdelegacion', 'movimiento', 'rechazo', 'gpo_actual', 'gpo_nuevo')
                ->where('lote_id', NULL)
		->where('solicitudes.rechazo_id', NULL)
		->orderBy('solicitudes.cuenta', 'asc')->get();

            return view(
                'ctas.admin.show_resume', [
                'solicitudes_sin_lote' => $solicitudes_sin_lote,
                'solicitudes_sin_lote2' => $solicitudes_sin_lote2,
                'listado_lotes'      => $listado_lotes,
            ]);
        }
        else {
            Log::info('Sin permiso-Ver Resumen-Admin. Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);
            abort(403,'No tiene permitido ver éste Resumen');
        }
    }

    public function show_admin_tabla() {
        if (Gate::allows('genera_tabla_oficio')) {

            Log::info('Genera Tabla para Oficio. Usuario id:' . Auth::id() . '|Nombre:|Del:' . Auth::user()->delegacion_id);

            $tabla_movimientos = DB::table('solicitudes')
                ->leftjoin('valijas', 'solicitudes.valija_id', '=', 'valijas.id')
                ->join('movimientos', 'solicitudes.movimiento_id', '=', 'movimientos.id')
                ->leftjoin('groups as gpo_a', 'solicitudes.gpo_actual_id', '=', 'gpo_a.id')
                ->leftjoin('groups as gpo_n', 'solicitudes.gpo_nuevo_id', '=', 'gpo_n.id')
                ->select('valijas.id as val_id', 'valijas.num_oficio_ca',
                    'solicitudes.id as sol_id', 'solicitudes.primer_apellido', 'solicitudes.segundo_apellido', 'solicitudes.nombre',
                    'solicitudes.cuenta', 'solicitudes.matricula', 'solicitudes.curp', 'solicitudes.archivo',
                    'gpo_a.name as gpo_a_name', 'gpo_n.name as gpo_n_name', 'movimientos.id as mov_id', 'movimientos.name as mov_name')
                ->where('solicitudes.rechazo_id', NULL)
                ->where('solicitudes.lote_id', NULL)
                ->where('valijas.origen_id', 2)
                ->orderBy('solicitudes.movimiento_id')
                ->orderBy('solicitudes.cuenta')
                ->get();

            return view(
                'ctas.admin.show_tabla', [
                'tabla_movimientos' => $tabla_movimientos,
            ]);
        }
        else {
            Log::info('Sin permiso-Generar Tabla. Usuario:' . Auth::user()->name . '|Del:' . Auth::user()->delegacion_id);

            abort(403,'No tiene permitido ver esta tabla');
        }
    }
}
