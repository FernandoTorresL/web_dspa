@extends('layouts.app')

@section('title', 'Consultar Solicitud')

@section('content')
    <div class="row">
        <a class="btn btn-default" href="{{ url('/') }}">Inicio</a>
        <a class="nav-link" href="{{ url()->previous() }}">Regresar</a>
    </div>
    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
    <br>
    <h3 class="card-title"><strong>Datos de la Solicitud</strong></h3>
    <br>

    @can('consultar_solicitudes_del')
        @include('ctas.solicitudes.solicitud')
    @else
        No estás autorizado a consultar solicitudes
    @endcan
@endsection
