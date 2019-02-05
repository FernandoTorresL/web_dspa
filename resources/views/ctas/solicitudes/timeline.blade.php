@extends('layouts.app')

@section('title', 'Timeline Solicitud')

@section('content')
    @php
        use Carbon\Carbon;
        setlocale(LC_TIME, 'es_ES');
        \Carbon\Carbon::setUtf8(false);
    @endphp

    <p>
        <a class="btn btn-default" href="{{ url('/') }}">Inicio</a>
        <a class="btn btn-default" href="{{ url()->previous() }}">Regresar</a>
    </p>

    <div class="card border-info">
        <div class="card-header">
            <h4 class="card-title">
                <strong>
                    Timeline detallado, solicitud {{ $datos_timeline->cuenta }}
                <span class="text-muted float-right">
                </span>
            </h4>
        </div>

        <div class="card-group">
            <div class="card">
                <div class="card-body border-light">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong class="text-success">
                                @if( isset($datos_timeline->resultado_solicitud) )
                                    {{--If solicitud has a response ... --}}
                                    @php
                                        $cuenta = $datos_timeline->resultado_solicitud->cuenta;
                                    @endphp
                                @else
                                    {{--  ...show the captured value --}}
                                    @php
                                        $cuenta = $datos_timeline->cuenta;
                                    @endphp
                                @endif
                                Solicitud
                            </strong>
                            <span class="card-text float-right">
                                {{ \Carbon\Carbon::parse($datos_timeline->fecha_solicitud_del)->formatLocalized('%d de %B, %Y') }}
                            </span>
                            <div>
                                {{ $datos_timeline->movimiento->name }} - {{ $datos_timeline->cuenta }} ({{ isset($datos_timeline->gpo_actual) ? $datos_timeline->gpo_actual->name : '' }} {{ isset($datos_timeline->gpo_nuevo) ? ' -> ' . $datos_timeline->gpo_nuevo->name : '' }})
                                Matrícula: {{ $datos_timeline->matricula }}
                                CURP: {{ $datos_timeline->curp }}
                            </div>
                            <div>
                                Nombre: {{ $datos_timeline->nombre }} {{ $datos_timeline->primer_apellido }} {{ $datos_timeline->segundo_apellido }}
                            </div>
                            @if( !isset($datos_timeline->valija) )
                                <div>
                                    Sin valija
                                </div>
                            @endif
                        </li>

                        @if( isset($datos_timeline->valija) )
                            <li class="list-group-item">
                                <div>
                                    <strong class="text-success">
                                        Valija
                                    </strong>
                                    <span class="card-text float-right">
                                    {{ \Carbon\Carbon::parse($datos_timeline->valija->fecha_valija_del)->formatLocalized('%d de %B, %Y') }}
                                </span>
                                    <div>
                                        Número oficio: {{ $datos_timeline->valija->num_oficio_del }}
                                    </div>
                                </div>
                                <div>
                                    Delegación: {{ $datos_timeline->delegacion->name }}
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div>
                                    <strong class="text-success">
                                        Recepción Gestión
                                    </strong>
                                    <span class="card-text float-right">
                                        {{ \Carbon\Carbon::parse($datos_timeline->valija->fecha_recepcion_ca)->formatLocalized('%d de %B, %Y') }}
                                    </span>
                                    <div>
                                        Núm. gestión: {{ $datos_timeline->valija->num_oficio_ca }}
                                    </div>
                                </div>
                                <div>
                                    Comentario: {{ $datos_timeline->valija->comment }}
                                </div>
                            </li>
                        @endif

                        <li class="list-group-item">
                            <div>
                                <strong class="text-success">
                                    Captura Solicitud
                                </strong>
                                <span class="card-text float-right">
                                    {{ \Carbon\Carbon::parse($datos_timeline->created_at)->formatLocalized('%d de %B, %Y. %H:%M') }}
                                </span>
                                <div>
                                    Capturado por: {{ $datos_timeline->user->name }}
                                </div>
                            </div>
                            <div>
                                {{-- Setting the solicitud status --}}
                                @if( isset($datos_timeline->rechazo) )
                                    Status:
                                    {{-- Solicitud was denny... --}}
                                    <span class="card-text text-danger">No procede. {{ $datos_timeline->rechazo->full_name }}</span>
                                @endif
                            </div>
                            <div>
                                Comentario: {{ $datos_timeline->comment }}
                            </div>
                        </li>

                        @if( isset($datos_timeline->lote) )
                            <li class="list-group-item">
                                <div>
                                    <strong class="text-success">
                                        Envío Mainframe
                                    </strong>
                                    <span class="card-text float-right">
                                        {{ \Carbon\Carbon::parse($datos_timeline->lote->fecha_oficio_lote)->formatLocalized('%d de %B, %Y') }}
                                    </span>
                                    <div>
                                        Lote: {{ $datos_timeline->lote->num_lote }}
                                    </div>
                                </div>
                                <div>
                                    Comentario: {{ $datos_timeline->lote->comment }}
                                </div>
                            </li>
                        @endif

                        @if( isset($datos_timeline->resultado_solicitud) )
                            <li class="list-group-item">
                                <div>
                                    <strong class="text-success">
                                        Respuesta Mainframe
                                    </strong>

                                    <span class="card-text float-right">
                                        {{ \Carbon\Carbon::parse($datos_timeline->resultado_solicitud->resultado_lote->attended_at)->formatLocalized('%d de %B, %Y. %H:%M') }}
                                    </span>

                                    @if( isset($datos_timeline->resultado_solicitud->rechazo_mainframe) )
                                        <div>
                                            Causa Rechazo:
                                            {{-- Solicitud was denny... --}}
                                            <span class="card-text text-danger">No procede. {{ $datos_timeline->resultado_solicitud->rechazo_mainframe->name }}</span>
                                        </div>
                                    @else
                                        <div>
                                            <span class="card-text text-success">Atendida. {{ $cuenta }}</span>
                                        </div>
                                        <div>
                                            Nombre Mainframe: {{ $datos_timeline->resultado_solicitud->name }}
                                        </div>
                                    @endif

                                    Comentario: {{ $datos_timeline->resultado_solicitud->comment }}
                                </div>
                            </li>
                        @endif


                    </ul>
                </div>
            </div>

        </div>

    </div>

@endsection
