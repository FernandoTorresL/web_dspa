@extends('layouts.app')

@section('title', 'Consultar Solicitud')

@section('content')
    <p>
        <a class="btn btn-default" href="{{ url('/') }}">Inicio</a>
        <a class="btn btn-default" href="{{ url()->previous() }}">Regresar</a>
    </p>
    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    @can('consultar_solicitudes_del')
        @include('ctas.solicitudes.solicitud')
    @else
        No estás autorizado a consultar solicitudes
    @endcan
@endsection
