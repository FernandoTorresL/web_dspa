@extends('layouts.app')

@section('title', 'Ctas')

@section('content')
    <div class="container">
        <div class="row">
                <a class="nav-link" href="{{ url('/') }}">Regresar</a>
        </div>

        @if(Auth::check())
            <div class="row">
            @if(Gate::allows('captura_completa_solicitudes', Auth::user()))
                @include('ctas.cardSolicitudesNC')
            @else
                @include('ctas.resumenctas')
                <div class="row">
                    {{--<div class="col-10 col-md-1">--}}
                    {{--</div>--}}
                    @include('ctas.cardInventario')
                    @include('ctas.cardSolicitudesDel')
                    {{--<div class="col-10 col-md-1">--}}
                    {{--</div>--}}
                </div>
            @endif
            </div>
        @else
            <p>No tienes permiso para ver esta página</p>
        @endif
    </div>
@endsection
