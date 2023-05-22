@extends('layouts.app')

@if ($delegacion_a_consultar->id == 0)
    @section('title', 'Ctas vigentes Nacional')
@else
    @section('title', 'Ctas vigentes ' . $delegacion_a_consultar->name)
@endif

@section('content')

    @if(Auth::check())

        <div class="btn text-white bg-primary">
            <p class="h6">
                Cuentas vigentes ({{ number_format( $total_active_accounts ) }}) -
                @if ($delegacion_a_consultar->id == 0)
                    Nacional - Todas las delegaciones
                @else
                    {{ env('OOAD') }}
                    {{ $delegacion_a_consultar->name }}
                @endif
            </p>
            <p class="small text-left">
                Cuentas vigentes: {{ number_format( $total_active_accounts ) }} |
                Cuentas únicas: {{ number_format( $total_user_id_list ) }}
            </p>
        </div>

        <br>
        <br>
        <div>
            @include('ctas.inventario.cifras_active_accounts_gral')
        </div>
        <br>

        @can('ver_lista_ctas_vigentes_gral')
            @include('ctas.inventario.list_active_accounts_gral')
        @endcan
    @endif

@endsection
