@extends('layouts.app')

@section('title', 'Ctas Delegación')

@section('content')
    <div class="container">
        <div class="row">
                <a class="nav-link" href="{{ url('/') }}">Regresar</a>
        </div>

        @if(session()->has('message'))
            <div class="alert alert-danger">
                {{ session()->get('message') }}
            </div>
        @endif

        <div class="row">
            <div class="card-body">
                <h4 class="card-title">{{ $primer_renglon }}</h4>
            </div>
        </div>

        @can('ver_resumen_del')
            @include('ctas.card_resumen')
        @endcan

        <div class="row">
            @can('ver_inventario_del')
                @include('ctas.card_inventario')
            @endcan

            @include('ctas.card_solicitudes')
        </div>
    </div>
@endsection
