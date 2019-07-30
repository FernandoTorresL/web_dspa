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
            <div class="card-header">Error 404</div>
                <div class="card-body">
                    <h5 class="card-title">Recurso no localizado</h5>
                    <p class="card-text">No se ha podido localizar lo que buscas. 
                    Si crees que esto es un error, por favor comunícate con los administradores del portal</p>
                </div>
                <div class="card-footer bg-transparent">Atte. Equipo DSPA</div>
        </div>

        <br>
    </div>
@endsection
