<?php

namespace App\Http\Controllers;

use App\Detalle_cta;
use App\Subdelegacion;
use Illuminate\Http\Request;
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
        $user = Auth::user()->name;
        $del_id = Auth::user()->delegacion_id;
        $del_name = Auth::user()->delegacion->name;

        Log::info('Visitando Ctas-Home. Usuario:' . $user . '|Del:(' . $del_id . ')-' . $del_name);

        $subdelegaciones = Subdelegacion::where('delegacion_id', $del_id)->where('num_sub', '>', 0)->orderBy('num_sub', 'asc')->get();

        $total_ctas =
            DB::table('detalle_ctas')->select('cuenta')->where('delegacion_id', $del_id)->distinct()->get()->count();

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

//        dd($total_ctas_SSJSAV);

        return view('ctas.home', [
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

            'del_id' => $del_id,
            'del_name' => $del_name,
        ]);
    }
}
