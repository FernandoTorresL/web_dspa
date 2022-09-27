@extends('layouts.app')

@section('title', 'Timeline Solicitud')

@section('content')
@php
    use App\Http\Helpers\Helpers;
@endphp

    <p>
        <a class="btn btn-default" href="{{ url('/') }}">Inicio</a>
        <a class="btn btn-default" href="{{ url()->previous() }}">Regresar</a>
    </p>

    <h4>Timeline detallado
        <a target="_blank" alt="Ver solicitud" href="/ctas/solicitudes/{{ $solicitud_t->id }}">
            {{ isset($solicitud_t->resultado_solicitud) ? $cta_resp : $solicitud_t->cuenta }}
        </a>
    </h4>
    <br>

    {{-- SOLICITUD --}}
    <div class="card border-primary w-50">
        <div class="card-header text-primary">
            <h5>
                <a target="_blank" alt="Ver solicitud" href="/ctas/solicitudes/{{ $solicitud_t->id }}">Solicitud</a>
                <span class="float-right">
                    Fecha formato: {{ Helpers::formatdate2($solicitud_t->fecha_solicitud_del) }}
                </span>
            </h5>
        </div>
        <div class="card-body">
            <h6 class="card-subtitle mb-2 small text-muted">
                {{ isset($solicitud_t->valija) ? '' : '(Solicitud sin valija)' }}
            </h6>
            <p class="card-text">Solicitud de {{ $solicitud_t->movimiento->name }} para {{ $solicitud_t->cuenta . $sol_gpo_detail }}</p>
            <p class="card-text">CURP: 
                <a target="_blank" alt="Ver solicitudes de esta CURP"
                    href="/ctas/solicitudes/search/cta?search_word={{ $solicitud_t->curp }}">
                        {{ $solicitud_t->curp }}
                </a>
            </p>
            <p class="card-text">Matrícula: 
                <a target="_blank" alt="Ver solicitudes de ésta matrícula"
                    href="/ctas/solicitudes/search/cta?search_word={{ $solicitud_t->matricula }}">
                        {{ $solicitud_t->matricula }}
                </a>
            </p>
            <p class="card-text">Nombre: {{ $solicitud_t->primer_apellido . '-' . $solicitud_t->segundo_apellido . '-' . $solicitud_t->nombre }}</p>
            <p>Capturada por: </p>
            <p>Fecha captura: {{ Helpers::formatdatetime2($solicitud_t->created_at) }} </p>
        </div>
    </div>
    {{-- FIN SOLICITUD --}}

    <br>
    <p class="small text-muted">
        Días entre fecha solicitud y fecha de la valija:
        {{ isset($solicitud_t->valija) ? Helpers::formatdif_dias2(
            date_create($solicitud_t->fecha_solicitud_del),
            date_create($solicitud_t->valija->fecha_valija_del) ) : '--'
        }}
    </p>

    {{-- VALIJA --}}
    <div class="card border-primary w-50">
        <div class="card-header text-primary">
            <h5>Valija
                <span class="float-right">
                    Fecha oficio: 
                    {{ isset($solicitud_t->valija) ? Helpers::formatdate2($solicitud_t->valija->fecha_valija_del) : '--' }}
                </span>
            </h5>
        </div>
        <div class="card-body">
            <h6 class="card-subtitle mb-2 small text-muted">
                {{ isset($solicitud_t->valija) ? '' : '(Solicitud sin valija)' }}
            </h6>
            <p class="card-text">Núm. de oficio origen: 
                {{ isset($solicitud_t->valija) ? $solicitud_t->valija->num_oficio_del : '--' }}
            </p>
            <p class="card-text">OOAD Remitente: 
                {{ isset($solicitud_t->valija) ? '(' . $solicitud_t->delegacion->id . ') ' . $solicitud_t->delegacion->name : '--' }}
            </p>
            <p>Capturada por: </p>
            <p>Fecha captura: 
                {{ isset($solicitud_t->valija) ? Helpers::formatdatetime2($solicitud_t->valija->created_at) : '--' }}
            </p>
        </div>
    </div>
    {{-- FIN VALIJA --}}

    <br>
    <p class="small text-muted">
        Días entre fecha de la valija y fecha recepción en la Gestión CA:
        {{ isset($solicitud_t->valija) ? Helpers::formatdif_dias2(
            date_create($solicitud_t->valija->fecha_valija_del),
            date_create($solicitud_t->valija->fecha_recepcion_ca) ) : '--'
        }}
    </p>

    {{-- GESTION --}}
    <div class="card border-primary w-50">
        <div class="card-header text-primary">
            <h5>Recepción Gestión CA
                <span class="float-right">
                    {{ isset($solicitud_t->valija) ? Helpers::formatdate2($solicitud_t->valija->fecha_recepcion_ca) : '--' }}
                </span>
            </h5>
        </div>
        <div class="card-body">
            <h6 class="card-subtitle mb-2 small text-muted">
                {{ isset($solicitud_t->valija) ? '' : '(Solicitud sin valija)' }}
            </h6>
            <p class="card-text">Núm. del área de gestión: 
                {{ isset($solicitud_t->valija) ? $solicitud_t->valija->num_oficio_ca : '--' }}
            </p>
            <p class="card-text">Comentario del área de gestión: 
                {{ isset($solicitud_t->valija) ? $solicitud_t->valija->comment : '--' }}
            </p>
        </div>
    </div>
    {{-- FIN GESTION --}}

    <br>
    <p class="small text-muted">Gestión tarda al menos un día (ya contabilizado) en entregar las valijas al personal que captura</p>
    <p class="small text-muted">
        Días entre recepción Gestión CA y la captura de la solicitud: 
        {{ isset($solicitud_t->valija) ? Helpers::formatdif_dias2(
            date_create($solicitud_t->valija->fecha_recepcion_ca),
            date_create($solicitud_t->valija->created_at) ) : '--'
        }}
    </p>
@php
    //-- Setting the solicitud status --}}
    $color_sol_cap = '';
    $rechazo_sol_cap = '--';
    if( isset($solicitud_t->rechazo) ) {
        $color_sol_cap = 'text-danger';
        $rechazo_sol_cap = 'No procede. ' . $solicitud_t->rechazo->full_name;
    }
@endphp

    {{-- CAPTURA DE SOLICITUD --}}
    <div class="card border-primary w-50">
        <div class="card-header text-primary">
            <h5>Captura de Solicitud<span class="float-right">{{ Helpers::formatdatetime2($solicitud_t->created_at) }}</span></h5>
        </div>
        <div class="card-body">
            <p>Capturado por: </p>
            <p>Modificada por: {{ $solicitud_t->user->name }}</p>
            <p>Comentario: {{ $solicitud_t->comment }}</p>
            <p>Causa del rechazo: <span class="{{ $color_sol_cap }}">{{ $rechazo_sol_cap }}</span></p>
            <p>Observaciones Nivel Central: {{ $solicitud_t->final_remark }}</p>
        </div>
    </div>
    {{-- FIN CAPTURA DE SOLICITUD --}}

    <br>
    <p class="small text-muted">
        Días entre captura de la solicitud y el envío del lote: 
        {{ isset($solicitud_t->lote) ? Helpers::formatdif_dias2(
            date_create($solicitud_t->created_at),
            date_create($solicitud_t->lote->fecha_oficio_lote) ) : '--'
        }}
    </p>

    {{-- ENVIO A MAINFRAME --}}
    <div class="card border-primary w-50">
        <div class="card-header text-primary">
            <h5>Envío a Mainframe
                <span class="float-right">
                    {{ isset($solicitud_t->lote) ? Helpers::formatdate2($solicitud_t->lote->fecha_oficio_lote) : '--' }}
                </span>
            </h5>
        </div>
        <div class="card-body">
            <p class="card-text">Lote: {{ isset($solicitud_t->lote) ? $solicitud_t->lote->num_lote : '--' }}</p>
            <p class="card-text">Creado por: {{ isset($solicitud_t->lote) ? $solicitud_t->lote->user->name : '--' }}</p>
            <p class="card-text">Ticket: {{ isset($solicitud_t->lote) ? $solicitud_t->lote->ticket_msi : '--' }}</p>
            <p class="card-text">Comentario: {{ isset($solicitud_t->lote) ? $solicitud_t->lote->comment : '--' }}</p>
        </div>
    </div>
    {{-- FIN ENVIO A MAINFRAME --}}

    <br>
    <p class="small text-muted">
        Días entre envío del lote y respuesta Mainframe: {{ $date_diff_lote_resp }}
    </p>
    {{-- RESPUESTA MAINFRAME --}}
    <div class="card border-primary w-50">
        <div class="card-header text-primary">
            <h5>Respuesta Mainframe
                <span class="float-right">{{ $fecha_resp }}</span>
            </h5>
        </div>
        <div class="card-body">
            <p class="card-text">Resultado: <span class="{{ $color_resp }}">{{ $rechazo_resp }}</span></p>
            <p class="card-text">Cuenta final: {{ $cta_resp }}</p>
            <p class="card-text">Nombre en Mainframe: {{ $nombre_resp }}</p>
            <p class="card-text">Resultado capturado por: {{ $user_resp }}</p>
            <p class="card-text">Reflejado en sistema el: {{ $fcaptura_resp }}</p>
            <p class="card-text">Comentario: {{ $comment_resp }}</p>
        </div>
    </div>
    {{-- FIN RESPUESTA MAINFRAME --}}

@endsection
