@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
    @guest
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <a class="nav-link" href="{{ route('login') }}">
                    <img class="d-block w-100" src="https://picsum.photos/800/400?image=3&text=First slide" alt="Entrar">
                    <div class="carousel-caption d-none d-md-block">
                        <h5  class="text-primary">Entrar</h5>
                        <p class="text-primary">Ingresa con tu correo y contraseña</p>
                    </div>
                </a>
            </div>
            <div class="carousel-item">
                <a class="nav-link" href="{{ route('register') }}">
                    <img class="d-block w-100" src="https://picsum.photos/800/400?image=0&text=Second slide" alt="Registrarse">
                    <div class="carousel-caption d-none d-md-block">
                        <h5 class="text-primary">Registrarse</h5>
                        <p class="text-primary">¿Aún no tienes cuenta? Ingresa aquí para registrarte</p>
                    </div>
                </a>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

        <div class="row">
            @forelse($messages as $message)
                <div class="col-6">
                    @include('messages.message')
                </div>
            @empty
                {{--<p>No hay mensajes destacados.</p>--}}
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
                <h1 class="h3">Módulo Gestión de Cuentas SINDO</h1>
                <a href="ctas">
                    <img class="img-thumbnail" src="https://picsum.photos/600/338?image=4">
                </a>
                <p class="text-muted">
                    Revisa inventario, solicitudes, estatus, etc.
                </p>
                <a href="ctas">
                    <p class="card-text">Ver Módulo</p>
                </a>
            </div>
        </div>
    @endguest

@endsection
