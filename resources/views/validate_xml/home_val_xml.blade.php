@extends('layouts.app')

@section('title', 'Valida XML-SIE')

@section('content')

    @if(session()->has('message'))
        <div class="alert alert-danger">
            {{ session()->get('message') }}
        </div>
    @endif

    <div class="card-header card text-white bg-warning">
        <p class="h5">
            Validador archivos XML-SIE - {{ $primer_renglon }}
        </p>
    </div>

    <br>

@endsection

