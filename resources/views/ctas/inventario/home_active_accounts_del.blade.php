@extends('layouts.app')

@section('title', 'Ctas vigentes')

@section('content')

    @if(Auth::check())

        <div class="btn text-white bg-primary">
            <p class="h6">
                Cuentas vigentes ({{ number_format( $total_active_accounts ) }}) -
                @if(Auth::user()->delegacion->id <> 9)
                    {{ env('OOAD') }}
                @endif
                {{ Auth::user()->delegacion->name }}
        </div>

        <br>
        <br>
        <div>
            @include('ctas.inventario.cifras_active_accounts_del')
        </div>
        <br>

        @can('ver_lista_ctas_vigentes_del')
            @include('ctas.inventario.list_active_accounts_del')
        @endcan
    @endif

@endsection
