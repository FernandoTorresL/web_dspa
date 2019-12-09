@extends('layouts.app')

@section('title', 'Error 500')

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
                <h6 class="card-subtitle mb-2">Error 500</h6>
            </div>

            <div class="card-body">
                <p class="card-text">Hubo un problema con tu petición.</p>
                <p><strong>¿De casualidad estabas utilizando Internet Explorer?</strong>
                Recuerda que se requiere utilizar Google Chrome como navegador.</p>
                <p>Si ese no es el problema, por favor comunicate con los administradores del Portal y comparte con nosotros la mayor cantidad 
                    de información que nos ayude a replicar el error y podamos ofrecerte una solución.</p>
            </div>

            <div class="card-footer bg-transparent">
                Atte. Equipo DSPA
            </div>
        </div>

        <br>
    </div>
@endsection
