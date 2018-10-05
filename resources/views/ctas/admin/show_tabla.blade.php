@extends('layouts.app')

@section('title', 'Genera Tabla')

@section('content')
    <p>
        <a class="btn btn-default" href="{{ url('/ctas') }}">Regresar</a>
    </p>

    @if(Auth::check())
            <div class="card text-white bg-primary">
                <div class="card-header">
                    <p class="h4">Tabla para Oficio</p>
                </div>
                <div class="card-body">
                    {{--<p class="card-title">Total de cuentas: {{ $listado_detalle_ctas->count() }}</p>--}}
                    {{--<p class="card-text">Fecha de corte: {{ date('d-M-Y', strtotime($listado_detalle_ctas->first()->inventory->cut_off_date)) }}</p>--}}
                </div>
            </div>

            @include('ctas.admin.genera_tabla')
    @endif

@endsection
