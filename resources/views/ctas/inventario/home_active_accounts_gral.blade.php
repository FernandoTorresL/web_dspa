@extends('layouts.app')

@section('title', 'Cuentas activas Afiliación - Nacional')

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

        <div class="card-header card text-white bg-primary">
            <p class="h4">
                Cuentas activas Afiliación - {{ $delegacion_a_consultar->id == 0 ? 'Nacional' : $delegacion_a_consultar->name  }}
            </p>
            <p>Cuenta vigentes:         {{ number_format( $total_active_accounts_gral ) }} |
            Cuentas vigentes únicas:    {{ number_format( $total_user_id_gral_list ) }} </p>
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
