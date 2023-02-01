@extends('layouts.app')

@if ($delegacion_a_consultar->id == 0)
    @section('title', '(Nacional) Listado ADMIN-OOAD Cuentas vigentes Afiliación')
@else
    @section('title', '(' . str_pad($delegacion_a_consultar->id , 2, '0', STR_PAD_LEFT) . ') Listado ADMIN-OOAD Cuentas vigentes Afiliación')
@endif

@section('content')

@php
    use Carbon\Carbon;
    setlocale(LC_TIME, 'es-ES');
    \Carbon\Carbon::setUtf8(false);
@endphp
    <p>
        <a class="btn btn-default" href="{{ url('/ctas') }}">Regresar</a>
    </p>

    @if(Auth::check())

        <div class="card-header card text-white bg-danger">
            <p class="h4">
                @if ($delegacion_a_consultar->id == 0)
                    Cuentas vigentes Afiliación ADMIN - Nacional
                @else
                    Cuentas vigentes Afiliación ADMIN - OOAD 
                    {{ $delegacion_a_consultar->name }} 
                    ({{ str_pad($delegacion_a_consultar->id , 2, '0', STR_PAD_LEFT) }})
                @endif
            </p>
            <p>
                Núm. total de registros: {{ number_format( $total_active_accounts ) }} |
                Cuentas vigentes únicas: {{ number_format( $total_user_id_list ) }}
            </p>
        </div>

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
