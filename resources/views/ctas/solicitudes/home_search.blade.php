@extends('layouts.app')

@section('title', 'Listado Solicitudes')

@section('content')
    <p>
        <a class="btn btn-default" href="{{ url('/') }}">Inicio</a>
        <a class="btn btn-default" href="{{ url()->previous() }}">Regresar</a>
    </p>

    @if(Auth::check())
        <div class="card text-white bg-primary">
            <div class="card-header">
                <p class="h4">Buscar Solicitudes - OOAD {{ Auth::user()->delegacion->name }} ({{ str_pad(Auth::user()->delegacion->id, 2, '0', STR_PAD_LEFT) }})</p>
            </div>
        </div>



    @endif

@endsection
