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
        <div class="card text-white bg-primary">
            <div class="card-header">
                <p class="h4">Inventario - Delegación {{ str_pad($user_del_id, 2, '0', STR_PAD_LEFT) }} {{ $user_del_name }}</p>
            </div>
            <div class="card-body">
                <p class="card-title">Total de cuentas: {{ number_format($list_inventario->total()) }} </p>
                <p class="card-text">Fecha de corte: {{ \Carbon\Carbon::parse($cut_off_date)->formatLocalized('%d de %B, %Y') }}</p>
            </div>
        </div>
        
        @include('ctas.inventario.list_new_ctas')

        @include('ctas.inventario.list_inventario')
    @endif

@endsection
