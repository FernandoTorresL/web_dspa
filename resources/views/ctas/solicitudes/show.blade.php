@extends('layouts.app')

@section('content')
    <div class="row">
        <a class="nav-link" href="{{ url('/') }}">Regresar</a>
    </div>
    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
    <br>
    <h1 class="h3">Solicitud: {{ $solicitud->id }}</h1>
    @include('ctas.solicitudes.solicitud')
@endsection
