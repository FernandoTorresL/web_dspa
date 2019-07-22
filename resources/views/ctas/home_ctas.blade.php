@extends('layouts.app')

@section('title', 'Módulo Gestión de Cuentas')

@section('content')

    <div class="container">
        <div class="row">
            <a class="nav-link" href="{{ url('/') }}">Inicio</a>
            <a class="nav-link" href="{{ url()->previous() }}">Regresar</a>
        </div>

        @if(session()->has('message'))
            <div class="alert alert-danger">
                {{ session()->get('message') }}
            </div>
        @endif

        <div class="card-header card text-white bg-primary">
            <p class="h4">
                {{ $primer_renglon }}
            </p>
        </div>
        <br>



        <div class="row">
            <div class="card-body">
                <h4 class="card-title"></h4>
            </div>
        </div>

        <div class="row">
            @can('ver_modulo_admin')
                @include('ctas.card_admin')
            @endcan
        </div>

        <div class="row">
            @can('ver_resumen_del')
                @include('ctas.card_resumen')
            @endcan
        </div>

        <div class="col-10 col-md-12">
            <br>
        </div>

        <div class="row">
            @canany( ['ver_inventario_del', 'ver_inventario_gral'] )
                @include('ctas.card_inventario')
            @endcanany

            @include('ctas.card_solicitudes')

            @can('ver_status_solicitudes')
                @include('ctas.card_status_solicitudes')
            @endcan
        </div>

        <div class="col-10 col-md-12">
            <br>
        </div>

    </div>
@endsection
