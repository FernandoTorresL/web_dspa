@extends('layouts.app')

@section('title', 'Listado Solicitudes')

@section('content')
    <p>
        <a class="btn btn-default" href="{{ url('/ctas') }}">Regresar</a>
    </p>

    @if(Auth::check())
        <div class="card text-white bg-primary">
            <div class="card-header">
                <p class="h4">OOAD {{ Auth::user()->delegacion->id }} - {{ Auth::user()->delegacion->name }} | Listado Solicitudes</p>
            </div>
        </div>
        <br>
        <br>

        @if(count($listado_solicitudes))
            <div class="table table-sm">
                <table class="table table-condensed">
                    <thead>
                    <tr class="small">
                        <th scope="col">#</th>
                        <th scope="col">Oficio-CA (Oficio Del)</th>
                        <th scope="col">Fecha Captura / Modificación</th>
                        <th scope="col">Última modificación</th>
                        <th scope="col">Delegación - Subdelegación</th>
                        <th scope="col">Nombre completo</th>
                        <th scope="col">Usuario (Mov) Lote</th>
                        <th scope="col">Gpo Actual -> Nuevo</th>
                        <th scope="col">Estatus</th>
                        <th scope="col">Causa Rechazo</th>
                        <th scope="col">Comentarios</th>
                        {{--<th scope="col">PDF</th>--}}
                    </tr>
                    </thead>
        @endif

        @php
            $var = 0;
        @endphp

        @forelse($listado_solicitudes as $solicitud)
            @php
                $var += 1;
            @endphp
                    <tbody>
                    <tr class="small @if(isset($solicitud->rechazo) || (isset($solicitud->resultado_solicitud->rechazo_mainframe))) table-danger @else @if(!isset($solicitud->resultado_solicitud)) table-warning @else table-success @endif @endif">
                        <th scope="row">{{ $var }}</th>
                        <td class="small">{{ isset($solicitud->valija) ? $solicitud->valija->num_oficio_ca.' ('.$solicitud->valija->num_oficio_del .')' : 'Sin valija'}}</td>
            @php
                $columna_fecha_usuario = date('d-M-Y', strtotime($solicitud->created_at));
                $columna_fecha_usuario2 = '';
            if ( $solicitud->created_at == $solicitud->updated_at )
                $columna_fecha_usuario2 = '';
            else
                $columna_fecha_usuario2 = date('d-M-Y', strtotime($solicitud->updated_at));
            @endphp
                        <td class="small">{{ $columna_fecha_usuario }} <br> {{ $columna_fecha_usuario2 }} </td>
                        <td class="small">{{ $solicitud->user->name }}</td>
                        <td class="small" >({{ $solicitud->delegacion->id }}) {{ $solicitud->delegacion->name }} - ({{ $solicitud->subdelegacion->num_sub }}) {{ $solicitud->subdelegacion->name }}</td>
                        <td class="small">{{ $solicitud->primer_apellido }} - {{ $solicitud->segundo_apellido }} - {{ $solicitud->nombre }}</td>
                        <td class="small">
                            <a target="_blank" alt="Ver/Editar" href="/ctas/solicitudes/{{ $solicitud->id }}">
                                {{ isset($solicitud->rechazo) ? $solicitud->cuenta : (isset($solicitud->resultado_solicitud) ? (isset($solicitud->resultado_solicitud->rechazo_mainframe) ? $solicitud->cuenta : $solicitud->resultado_solicitud->cuenta) : $solicitud->cuenta )  }}
                                ({{$solicitud->movimiento->name }})
                            </a> Lote: {{ isset($solicitud->lote) ? $solicitud->lote->num_lote : '--' }}
                        </td>
                        <td class="small">{{ isset($solicitud->gpo_actual) ? $solicitud->gpo_actual->name : '' }} -> {{ isset($solicitud->gpo_nuevo) ? $solicitud->gpo_nuevo->name : '' }}</td>
                        <td class="small @if(isset($solicitud->rechazo) || (isset($solicitud->resultado_solicitud->rechazo_mainframe))) text-danger @else @if(isset($solicitud->lote) && (!isset($solicitud->resultado_solicitud))) text-warning @else text-success @endif @endif">
                            {{ isset($solicitud->rechazo) ? 'NO PROCEDE' : (isset($solicitud->resultado_solicitud) ? (isset($solicitud->resultado_solicitud->rechazo_mainframe) ? 'NO PROCEDE' : 'ATENDIDA') : 'EN ESPERA DE RESPUESTA' ) }}</td>
                        <td class="small @if(isset($solicitud->rechazo) || isset($solicitud->resultado_solicitud->rechazo_mainframe)) text-danger @endif">
                            {{ isset($solicitud->rechazo) ? $solicitud->rechazo->full_name : (isset($solicitud->resultado_solicitud) ? '/ '.(isset($solicitud->resultado_solicitud->rechazo_mainframe) ? $solicitud->resultado_solicitud->rechazo_mainframe->name : '' ) : '') }}</td>
                        <td class="small">{{ $solicitud->comment . (isset($solicitud->resultado_solicitud) ? (isset($solicitud->resultado_solicitud->comment) ? '/ ' : '').$solicitud->resultado_solicitud->comment : '') }}</td>
                </tr>
                </tbody>
            @empty
                <p>No hay solicitudes recientes</p>
        @endforelse

        @if(count($listado_solicitudes))
                </table>
            </div>
        @endif
    @endif

@endsection
