@extends('layouts.app')

@section('title', 'Cuentas activas Afiliación - OOAD')

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
                Cuentas activas Afiliación - OOAD {{ $user_del_name }} ({{ str_pad($user_del_id, 2, '0', STR_PAD_LEFT) }})
            </p>
            <p>TOTAL: {{ number_format( $total_active_accounts ) }} cuentas vigentes
        </div>

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
