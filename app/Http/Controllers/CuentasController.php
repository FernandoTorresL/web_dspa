<?php

namespace App\Http\Controllers;

use App\Subdelegacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
}
