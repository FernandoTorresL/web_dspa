@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
    <div class="jumbotron text-center">
        <h1>Portal de la División de Soporte a los Procesos de Afiliación</h1>
        <nav>
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link" href="">Inicio</a>
                </li>
            </ul>
        </nav>
    </div>

    @guest
        <div class="row">
            <form action="/messages/create" method="POST">
                {{--With Boostrap 4, "has-danger" and "form-control-feedback" doesnt work anymore--}}
                <div class="form-group">
                    {{ csrf_field() }}
                    <input type="text" name="message" class="form-control @if($errors->has('message')) is-invalid @endif" placeholder="Qué estás pensando?">
                    {{--@if ($errors->any())--}}
                    @if ($errors->has('message'))
                        @foreach($errors->get('message') as $error)
                            <div class="invalid-feedback">{{ $error }}</div>
                        @endforeach
                    @endif
                </div>
            </form>
        </div>

        <div class="row">
            @forelse($messages as $message)
                <div class="col-6">
                    @include('messages.message')
                </div>
            @empty
                <p>No hay mensajes destacados.</p>
            @endforelse

            @if(count($messages))
                <div class="mt-2 mx-auto">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="row">
            <div class="col-6">
                <a href="ctas">
                    <h1 class="h3">Gestión de Cuentas</h1>
                    <img class="img-thumbnail" src="https://picsum.photos/600/338?image=4">
                </a>
                <p class="card-text">
                <div class="text-muted">Revisa inventario, solicitudes, estatus, etc.</div>
                <a href="ctas">Ver más</a>
                </p>
            </div>

            <div class="col-6">
                <a href="">
                    <h1 class="h3">Módulo en construcción</h1>
                    <img class="img-thumbnail" src="https://picsum.photos/600/338?image=930">
                </a>
                <p class="card-text">
                <div class="text-muted">Descripción...</div>
                <a href="">Ver más</a>
                </p>
            </div>
        </div>
    @endguest

@endsection
