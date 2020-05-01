@extends('layouts.app')

@section('title', 'Error 404')

@section('content')

    <div class="container">
        <div class="row">
            <a class="nav-link" href="{{ url('/') }}">Inicio</a>
            <a class="nav-link" href="{{ url()->previous() }}">Regresar</a>
        </div>

        <br>
        <div class="card text-white bg-danger mb-6" style="max-width: 25rem;">

            <div class="card-header">
                <h5 class="card-title">{{ env('APP_NAME') }}</h5>
                <h6 class="card-subtitle mb-2">Error 404 - Recurso no localizado</h6>
            </div>

            <div class="card-body">
                
                <p class="card-text">No se ha podido localizar lo que buscas. 
                    Si crees que esto es un error, por favor comunícate con los administradores del portal y proporciona la ruta (URL) que muestra este navegador.</p>
            </div>

            <div class="card-footer bg-transparent">
                Atte. Equipo DSPA
            </div>
        </div>

        <br>
    </div>
@endsection
