@extends('layouts.app')

@section('title', 'Ctas Delegación')

@section('content')
    <div class="container">
        <div class="row">
            <a class="btn btn-default" href="{{ url('/') }}">Inicio</a>
            <a class="nav-link" href="{{ url()->previous() }}">Regresar</a>
        </div>

        @if(session()->has('message'))
            <div class="alert alert-danger">
                {{ session()->get('message') }}
            </div>
        @endif

        <div class="row">
            <div class="card-body">
                <h4 class="card-title">{{ $primer_renglon }}</h4>
            </div>
        </div>

        @can('ver_resumen_del')
            @include('ctas.card_resumen')
        @endcan

        <div class="row">
            @can('ver_inventario_del')
                @include('ctas.card_inventario')
            @endcan

            @include('ctas.card_solicitudes')
        </div>

        <div class="row">
            @can('ver_status_solicitudes')
                    @include('ctas.card_status_solicitudes')
            @endcan
        </div>

    </div>
@endsection
