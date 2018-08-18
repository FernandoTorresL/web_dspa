<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CuentasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        return view('ctas.home');
    }
}
