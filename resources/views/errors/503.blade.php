@extends('layouts.app')

@section('title', 'Error 503')

@section('content')

    <div class="container">
        <div class="row">
            <a class="nav-link" href="{{ url('/') }}">Inicio</a>
            <a class="nav-link" href="{{ url()->previous() }}">Regresar</a>
        </div>

        <br>
        <div class="card text-dark bg-warning mb-6" style="max-width: 25rem;">

            <div class="card-header">
                <h5 class="card-title">{{ env('APP_NAME') }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">Sitio en mantenimiento</h6>
            </div>

            <div class="card-body">
                
                <p class="card-text">
                    Pedimos disculpas por los inconvenientes ocasionados pero estamos trabajando en cosas interesantes. 
                </p>
                <p>¡Pronto estaremos en línea de nuevo!</p>
            </div>

            <div class="card-footer bg-transparent">
                Atte. Equipo DSPA
            </div>
        </div>

        <br>
    </div>
@endsection
