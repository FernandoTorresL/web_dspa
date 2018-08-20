<?php

namespace App\Http\Controllers;

use App\Detalle_cta;
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

        $total_ctas_SSJSAV =
            DB::table('detalle_ctas')->select('cuenta')->where('delegacion_id', $del_id)->where('gpo_owner_id', 7)->distinct()->get()->count();

//        dd($total_ctas_SSJSAV);

        return view('ctas.home', [
            'total_ctas_SSJSAV' => $total_ctas_SSJSAV,

            'del_id' => $del_id,
            'del_name' => $del_name,
        ]);
    }
}
