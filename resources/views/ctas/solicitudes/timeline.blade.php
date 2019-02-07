@extends('layouts.app')

@section('title', 'Timeline Solicitud')

@section('content')
    <p>
        <a class="btn btn-default" href="{{ url('/') }}">Inicio</a>
        <a class="btn btn-default" href="{{ url()->previous() }}">Regresar</a>
    </p>

    <h4>Timeline detallado, solicitud {{ $cuenta_sol }}</h4>

<div class="justify-content-center">
    <div class="card border-primary w-50">
        <div class="card-header text-primary">
            <h5>Solicitud<span class="float-right">{{ $fecha_sol }}</span></h5>
        </div>
        <div class="card-body">
            <h6 class="card-subtitle mb-2 text-muted">{{ $subt_val }}</h6>
            <p class="card-text">Trámite: {{ $titulo_sol }}</p>
            <p class="card-text">CURP: {{ $curp_sol }}</p>
            <p class="card-text">Matrícula: {{ $matricula_sol }}</p>
            <p class="card-text">Nombre: {{ $nombre_sol }}</p>
        </div>
    </div>

    <div class="card border-primary w-50">
        <div class="card-header text-primary">
            <h5>Valija<span class="float-right">{{ $fecha_val }}</span></h5>
        </div>
        <div class="card-body">
            <h6 class="card-subtitle mb-2 text-muted"></h6>
            <p class="card-text">Núm. de oficio origen: {{ $of_val }}</p>
            <p class="card-text">Remitente: {{ $del_val }}</p>
        </div>
    </div>

    <div class="card border-primary w-50">
        <div class="card-header text-primary">
            <h5>Recepción Gestión CA<span class="float-right">{{ $fecha_gestion }}</span></h5>
        </div>
        <div class="card-body">
            <h6 class="card-subtitle mb-2 text-muted"></h6>
            <p class="card-text">Núm. del área de gestión: {{ $num_gestion }}</p>
            <p class="card-text">Comentario del área de gestión: {{ $comment_gestion }}</p>
        </div>
    </div>

    <div class="card border-primary w-50">
        <div class="card-header text-primary">
            <h5>Captura de datos Solicitud<span class="float-right">{{ $fecha_sol_cap }}</span></h5>
        </div>
        <div class="card-body">
            <h6 class="card-subtitle mb-2 text-muted"></h6>
            <p class="card-text">Capturado por: {{ $user_sol_cap }}</p>
            <p class="card-text">Causa del rechazo: <span class="{{ $color_sol_cap }}">{{ $rechazo_sol_cap }}</span></p>
            <p class="card-text">Comentario: {{ $comment_sol_cap }}</p>
        </div>
    </div>

    <div class="card border-primary w-50">
        <div class="card-header text-primary">
            <h5>Envío a Mainframe<span class="float-right">{{ $fecha_lote }}</span></h5>
        </div>
        <div class="card-body">
            <p class="card-text">Lote: {{ $num_lote }}</p>
            <p class="card-text">Comentario: {{ $comment_lote }}</p>
        </div>
    </div>

    <div class="card border-primary w-50">
        <div class="card-header text-primary">
            <h5>Respuesta Mainframe<span class="float-right">{{ $fecha_resp }}</span></h5>
        </div>
        <div class="card-body">
            <p class="card-text">Resultado: <span class="{{ $color_resp }}">{{ $rechazo_resp }}</span></p>
            <p class="card-text">Cuenta final: {{ $cta_resp }}</p>
            <p class="card-text">Nombre en Mainframe: {{ $nombre_resp }}</p>
            <p class="card-text">Comentario: {{ $comment_resp }}</p>
        </div>
    </div>
@endsection
