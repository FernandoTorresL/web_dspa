<?php

namespace App\Http\Controllers;

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
        //Get user
        $user = Auth::user();
        //Get delegation_id
        $del = Auth::user()->delegacion_id;

        Log::info('Visitando Ctas-Home. User:' . $user->name . '|Del:' . $del);

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

            $primer_renglon = 'Delegación ' . $del . ' ' . $user->delegacion->name;
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

        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_del_id = Auth::user()->delegacion_id;
        $texto_log = '|User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id;

        if (Gate::allows('ver_resumen_admin_ctas')) {
            Log::info('Ver Resumen' . $texto_log);

            if (Auth::user()->hasRole('capturista_dspa'))
            {
                $primer_renglon = 'Nivel Central - DSPA';
            }

            $solicitudes_sin_lote = DB::table('solicitudes')
                ->leftjoin('valijas', 'solicitudes.valija_id', '=', 'valijas.id')
                ->join('movimientos', 'solicitudes.movimiento_id', '=', 'movimientos.id')
                ->select('valijas.origen_id', 'movimientos.name', DB::raw('COUNT(solicitudes.id) as total_solicitudes'))
                ->where('solicitudes.lote_id','=', NULL)
                ->groupBy('valijas.origen_id', 'movimientos.name')
                ->orderBy('origen_id')->orderBy('name')
                ->get();


            $listado_lotes = DB::table('lotes')
                                ->leftjoin('resultado_lotes', 'lotes.id', '=', 'resultado_lotes.lote_id')
                                ->leftjoin('solicitudes', 'lotes.id', '=', 'solicitudes.lote_id')
                                ->select('lotes.num_lote', 'lotes.num_oficio_ca', 'lotes.fecha_oficio_lote', 'lotes.ticket_msi', 'lotes.comment', 'resultado_lotes.attended_at', DB::raw('COUNT(solicitudes.id) as total_solicitudes'))
                                ->groupBy('lotes.num_lote', 'lotes.num_oficio_ca', 'lotes.fecha_oficio_lote', 'lotes.ticket_msi', 'lotes.comment', 'resultado_lotes.attended_at')
                                ->orderBy('lotes.id', 'desc')->limit(20)->get();

            $solicitudes_sin_lote2 = Solicitud::select('id', 'lote_id', 'valija_id', 'archivo', 'created_at', 'updated_at', 'delegacion_id', 'subdelegacion_id',
                'cuenta', 'nombre', 'primer_apellido', 'segundo_apellido', 'movimiento_id', 'rechazo_id', 'final_remark', 'comment', 'user_id', 'gpo_actual_id', 'gpo_nuevo_id', 'matricula', 'curp')
                ->with('user', 'valija', 'delegacion', 'subdelegacion', 'movimiento', 'rechazo', 'gpo_actual', 'gpo_nuevo')
                ->orderBy('id', 'desc')
                ->limit(250)
                ->get();

            return view(
                'ctas.admin.show_resume', [
                'solicitudes_sin_lote' => $solicitudes_sin_lote,
                'solicitudes_sin_lote2' => $solicitudes_sin_lote2,
                'listado_lotes'      => $listado_lotes,
            ]);
        }
        else {
            Log::info('Sin permiso-Ver Resumen-Admin' . $texto_log);

            abort(403,'No tiene permitido ver esta tabla resumen');
        }
    }

    public function show_admin_tabla() {
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $user_del_id = Auth::user()->delegacion_id;
        $texto_log = '|User_id:' . $user_id . '|User:' . $user_name . '|Del:' . $user_del_id;

        if (Gate::allows('genera_tabla_oficio')) {

            Log::info('Genera Tabla' . $texto_log);

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
                ->orderBy('solicitudes.movimiento_id')
                ->orderBy('solicitudes.cuenta')
                ->orderBy('valijas.num_oficio_ca')
                ->get();

            $first_query =
                DB::table('solicitudes')
                    ->leftjoin('valijas', 'solicitudes.valija_id', '=', 'valijas.id')
                    ->select('valijas.id',
                        'valijas.num_oficio_del',
                        'valijas.num_oficio_ca',
                        'valijas.delegacion_id',
                        'solicitudes.delegacion_id as sol_del_id',
                        DB::raw('count(solicitudes.id) as soli_count') )
                    ->where('solicitudes.lote_id', NULL)
                    ->where('valijas.id', '<>', NULL)
                    ->groupBy('valijas.id',
                        'valijas.num_oficio_del',
                        'valijas.num_oficio_ca',
                        'valijas.delegacion_id',
                        'solicitudes.delegacion_id')
                    ->orderBy('valijas.num_oficio_ca');

            $listado_valijas =
                DB::table('solicitudes')
                    ->leftjoin('valijas', 'solicitudes.valija_id', '=', 'valijas.id')
                    ->select('valijas.id',
                        'valijas.num_oficio_del',
                        'valijas.num_oficio_ca',
                        'valijas.delegacion_id',
                        'solicitudes.delegacion_id as sol_del_id',
                        DB::raw('count(solicitudes.id) as soli_count') )
                    ->where('solicitudes.lote_id', NULL)
                    ->where('valijas.id',NULL)
                    ->groupBy('valijas.id',
                        'valijas.num_oficio_del',
                        'valijas.num_oficio_ca',
                        'valijas.delegacion_id',
                        'solicitudes.delegacion_id')
                    ->orderBy('valijas.delegacion_id')
                    ->union($first_query)
                    ->get();

            $listado_mov_rechazados =
                Solicitud::with([
                    'valija',
                    'delegacion',
                    'subdelegacion',
                    'movimiento',
                    'rechazo',
                    'gpo_actual',
                    'gpo_nuevo',
                    'lote'])
                ->where('lote_id', 401)
                ->where('rechazo_id', '<>', NULL)
                ->orderBy('solicitudes.movimiento_id')
                ->orderBy('solicitudes.cuenta')
                ->get();

            return view(
                'ctas.admin.show_tabla', [
                'tabla_movimientos'      => $tabla_movimientos,
                'listado_valijas'        => $listado_valijas,
                'listado_mov_rechazados' => $listado_mov_rechazados,
            ]);
        }
        else {
            Log::info('Sin permiso-Generar Tabla' . $texto_log);

            abort(403,'No tiene permitido ver esta tabla');
        }
    }
}
