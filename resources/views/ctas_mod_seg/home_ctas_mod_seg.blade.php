@extends('layouts.app')

@section('title', 'Gestión de Cuentas del Módulo de Seguimiento')

@section('content')

    @if(session()->has('message'))
        <div class="alert alert-danger">
            {{ session()->get('message') }}
        </div>
    @endif

    <div class="card-header card text-white bg-success">
        <p class="h6">
            Módulo de Seguimiento de Solicitudes Patronales - {{ $primer_renglon }}
        </p>
    </div>

    <br>

    <div class="row h7">
        @can('ver_modulo_admin_ctas_mod_seg')
            @include('ctas_mod_seg.card_admin')
        @endcan

<!--         @can('ver_resumen_del')
            {{-- @include('ctas_mod_seg.card_resumen') --}}
        @endcan

        @canany( ['ver_lista_ctas_vigentes_gral'] )
            @include('ctas_mod_seg.card_active_accounts_gral')
        @endcanany -->
    </div>
    <br>
    <div class="row h7">

        @include('ctas_mod_seg.card_mod_seg_solicitudes')

        @canany( ['ver_lista_ctas_mod_seg_vigentes_del'] )
            @include('ctas_mod_seg.card_active_accounts_del')
        @endcanany

        @can('ver_status_sol_mod_seg')
            @include('ctas_mod_seg.card_status_solicitudes')
        @endcan

    </div>

    <br>

@endsection

