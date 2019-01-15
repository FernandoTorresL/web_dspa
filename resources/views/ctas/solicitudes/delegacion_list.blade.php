@extends('layouts.app')

@section('title', 'Listado Solicitudes')

@section('content')
    <p>
        <a class="btn btn-default" href="{{ url('/ctas') }}">Regresar</a>
    </p>

    @if(Auth::check())
        <div class="card text-white bg-primary">
            <div class="card-header">
                <p class="h4">Estatus Solicitudes - Delegación {{ str_pad(Auth::user()->delegacion->id, 2, '0', STR_PAD_LEFT) }} - {{ Auth::user()->delegacion->name }}</p>
            </div>
        </div>

        @include('ctas.solicitudes.list')

    @endif

@endsection
