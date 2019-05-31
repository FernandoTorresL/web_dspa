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
    </div>

        @include('ctas.admin.genera_tabla')

        @include('ctas.admin.genera_tabla_rechazos')

        @include('ctas.admin.genera_tabla_valijas')
    @endif

@endsection
