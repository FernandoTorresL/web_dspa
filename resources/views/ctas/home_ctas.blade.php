@extends('layouts.app')

@section('title', 'Gestión de Cuentas')

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

        <div class="card-header card text-white bg-success">
            <p class="h4">Gestión de Cuentas - {{ $primer_renglon }}</p>
            {{-- <p>Total Cuentas: {{ number_format( $total_ctas_Ctas ) }} = 
            {{ number_format( $total_inv_Ctas ) }} en inventario
            + {{ number_format( $nuevos_Ctas ) }} nuevas 
            - {{ number_format( $bajas_Ctas ) }} bajas</p> --}}
        </div>

        <div>
            <br>
        </div>

        <div class="row">
            @can('ver_modulo_admin')
                @include('ctas.card_admin')
            @endcan

            @can('ver_resumen_del')
                {{-- @include('ctas.card_resumen') --}}
            @endcan
        </div>

        <div>
            <br>
        </div>

        <div class="row">

            @include('ctas.card_solicitudes')

            @can('ver_status_solicitudes')
                @include('ctas.card_status_solicitudes')
            @endcan
        </div>

        <div>
            <br>
        </div>

        <div class="row">
            @canany( ['ver_lista_ctas_vigentes_del'] )
                @include('ctas.card_active_accounts')
            @endcanany

            @canany( ['ver_inventario_del', 'ver_inventario_gral'] )
                @include('ctas.card_inventario')
            @endcanany
        </div>

    </div>
@endsection
