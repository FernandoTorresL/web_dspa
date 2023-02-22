@extends('layouts.app')

@section('title', 'Gestión de Cuentas')

@section('content')

    @if(session()->has('message'))
        <div class="alert alert-danger">
            {{ session()->get('message') }}
        </div>
    @endif

    <div class="card-header card text-white bg-success">
        <p class="h6">
            Gestión de Cuentas - {{ $primer_renglon }}
        </p>
    </div>

    <br>

    <div class="row h7">
        @can('ver_modulo_admin')
            @include('ctas.card_admin')
        @endcan

        @can('ver_resumen_del')
            {{-- @include('ctas.card_resumen') --}}
        @endcan

        @canany( ['ver_lista_ctas_vigentes_gral'] )
            @include('ctas.card_active_accounts_gral')
        @endcanany
    </div>
    <br>
    <div class="row h7">

        @include('ctas.card_solicitudes')

        @can('ver_status_solicitudes')
            @include('ctas.card_status_solicitudes')
        @endcan

        @canany( ['ver_lista_ctas_vigentes_del'] )
            @include('ctas.card_active_accounts_del')
        @endcanany

        {{--        @canany( ['ver_inventario_del', 'ver_inventario_gral'] )
                @include('ctas.card_inventario')
            @endcanany --}}
    </div>

    <br>

@endsection

