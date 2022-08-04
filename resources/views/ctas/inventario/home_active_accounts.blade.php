@extends('layouts.app')

@section('title', 'Cuentas Activas')

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
                    Cuentas Activas - Delegación {{ str_pad($user_del_id, 2, '0', STR_PAD_LEFT) }} {{ $user_del_name }}
                </p>
                <p>TOTAL: {{ number_format( $total_active_accounts ) }} cuentas vigentes
            </div>
            <br>
        <hr>

        @include('ctas.inventario.list_active_accounts')
    @endif

@endsection
