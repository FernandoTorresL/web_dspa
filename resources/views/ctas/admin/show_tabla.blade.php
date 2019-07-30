@extends('layouts.app')

@section('title', 'Genera Tabla')

@section('content')
    <p>
        <a class="btn btn-default" href="{{ url('/ctas') }}">Regresar</a>
    </p>

    @if(Auth::check())
    <div class="card-header card text-white bg-primary">
        <p class="h4">Tabla para Oficio</p>
        @if( isset( $info_lote ) )
            <p>Lote: {{ $info_lote->num_lote }} id: {{ $info_lote->id }}</p>
        @endif
        @if( isset( $solicitud_id ) )
            <p>Solicitudes <= {{ $solicitud_id }}</p>
        @endif
        <br>
    </div>

        @include('ctas.admin.genera_tabla')

        @include('ctas.admin.genera_tabla_rechazos')

        @include('ctas.admin.genera_tabla_valijas')
    @endif

@endsection
