@extends('layouts.app')

@section('title', 'Inventario')

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
                    Inventario - Delegación {{ str_pad($user_del_id, 2, '0', STR_PAD_LEFT) }} {{ $user_del_name }}
                </p>
                <p>TOTAL: {{ number_format( $total_inventario ) }} + {{ number_format( $solicitudes->count() ) }} cuentas nuevas
                Corte: {{ \Carbon\Carbon::parse($cut_off_date)->formatLocalized('%d de %B, %Y') }}</p>
            </div>
            <br>
        @include('ctas.inventario.list_new_ctas')

        <hr>

        @include('ctas.inventario.list_inventario')
    @endif

@endsection
