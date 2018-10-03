@extends('layouts.app')

@section('title', 'Consultar Valija')

@section('content')
    <div class="row">
        <a class="nav-link" href="{{ url('/ctas') }}">Regresar</a>
    </div>
    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
    <br>
    <h3 class="card-title"><strong>Datos de la Valija</strong></h3>
    <br>
    @include('ctas.valijas.valija')
@endsection
