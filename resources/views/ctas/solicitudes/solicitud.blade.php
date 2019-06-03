<h4 class="card-title">
    <strong>
        @php
            use Carbon\Carbon;
            setlocale(LC_TIME, 'es-ES');
            \Carbon\Carbon::setUtf8(false);
        @endphp

        @if( isset($solicitud->resultado_solicitud) )
            {{--If solicitud has a response ... --}}
            @php
                $cuenta = $solicitud->resultado_solicitud->cuenta;
            @endphp
        @else
            {{--  ...show the captured value --}}
            @php
                $cuenta = $solicitud->cuenta;
            @endphp
        @endif

        SOLICITUD {{ $solicitud->movimiento->name }}
        <span class="text-success">{{ $cuenta }}</span>
        ({{ isset($solicitud->gpo_actual) ? $solicitud->gpo_actual->name : '' }}
        {{ isset($solicitud->gpo_nuevo)&&isset($solicitud->gpo_actual) ? '->' : '' }}
        {{ isset($solicitud->gpo_nuevo) ? $solicitud->gpo_nuevo->name : '' }})
        -
        <strong>{{ isset($solicitud->valija) ? 'Valija '.str_pad($solicitud->valija->delegacion->id, 2, '0', STR_PAD_LEFT).'-'.$solicitud->valija->num_oficio_ca : '(Sin valija)' }} </strong>
        </strong>
        @can('ver_timeline_solicitudes')
            <a class="" href="{{ url('/ctas/solicitudes/timeline/'.$solicitud->id) }}">Ver Timeline</a>
        @endcan
        <span class="text-muted float-right">
            @if (isset($solicitud->archivo))
                <a href="{{ $solicitud->archivo }}" target="_blank">Ver PDF</a>
            @endif
        </span>
</h4>

<div class="card border-info">
    <div class="card-header">
        <h4 class="card-title">
            <span class="text-muted">
                {{ $solicitud->primer_apellido }}-{{ $solicitud->segundo_apellido }}-{{ $solicitud->nombre }}
            </span>
        </h4>
        <h4 class="card-title">
                Fecha solicitud: {{ \Carbon\Carbon::parse($solicitud->fecha_solicitud_del)->formatLocalized('%d de %B, %Y') }}
            <span class="text-muted float-right">
                @if( ( !isset($solicitud->lote_id) && (!isset($solicitud->rechazo) && !isset($solicitud->resultado_solicitud->rechazo_mainframe)) || Auth::user()->id == 1 ) )
                    @can('editar_solicitudes_user_nc')
                        <a class="nav-link" href="{{ url('/ctas/solicitudes/editNC/'.$solicitud->id) }}">Editar</a>
                    @elsecan('editar_solicitudes_del')
                        <a class="nav-link" href="{{ url('/ctas/solicitudes/edit/'.$solicitud->id) }}">Editar</a>
                    @endcan
                @else

                @endif
            </span>
        </h4>
    </div>

    <div class="card-group">
        <div class="card">
            <div class="card-body border-light">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>CURP: </strong><span class="card-text float-right">{{ $solicitud->curp }}</span>
                        <div>
                            <strong>Matrícula: </strong><span class="card-text float-right">{{ $solicitud->matricula }}</span>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div>
                            <strong>Delegación: </strong>
                            <span class="card-text float-right">
                                {{ str_pad($solicitud->delegacion->id, 2, '0', STR_PAD_LEFT)  }} - {{ $solicitud->delegacion->name }}
                            </span>
                        </div>
                        <div>
                            <strong>Subdelegación: </strong><span class="card-text float-right text-truncate">{{ str_pad($solicitud->subdelegacion->num_sub, 2, '0', STR_PAD_LEFT) }} - {{ $solicitud->subdelegacion->name }}</span>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <strong>Status: </strong>
                        {{-- Setting the solicitud status --}}
                        @if( isset($solicitud->rechazo) || isset($solicitud->resultado_solicitud->rechazo_mainframe) )
                            {{-- Solicitud was denny... --}}
                            <span class="card-text float-right text-danger">No procede</span>
                        @else
                            @if( !isset($solicitud->resultado_solicitud) )
                                {{-- There's not response for the solicitud --}}
                                @if( isset($solicitud->lote) )
                                    {{-- This solicitud has a lote and we're waiting for response --}}
                                    <span class="card-text float-right text-warning">Enviada a Mainframe. En espera de respuesta</span>
                                @else
                                    {{-- We're analizing your solicitud --}}
                                    <span class="card-text float-right text-primary">En revisión por personal CA-DSPA</span>
                                @endif

                                {{--@elseif( $solicitud->resultado_solicitud->status == 1 )
                                    --}}{{-- There's an response, but we have to send again the solicitud --}}{{--
                                    <td class="text-warning">PENDIENTE</td>--}}
                            @else
                                <span class="card-text float-right text-success">Atendida</span>
                            @endif
                        @endif
                        <div>
                            <strong>Causa de rechazo: </strong>
                            <span class="card-text float-right @if(isset($solicitud->rechazo) || isset($solicitud->resultado_solicitud->rechazo_mainframe)) text-danger @endif">
                                {{--{{ isset($solicitud->rechazo) ? $solicitud->rechazo->full_name : '' }}--}}
                                {{ isset($solicitud->rechazo) ? $solicitud->rechazo->full_name : (isset($solicitud->resultado_solicitud) ? '/ '.(isset($solicitud->resultado_solicitud->rechazo_mainframe) ? $solicitud->resultado_solicitud->rechazo_mainframe->name : '' ) : '') }}
                            </span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-body border-light">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Capturado por: </strong>
                        <span class="card-text float-right">
                            {{ $solicitud_hasBeenModified ? $solicitud->hist_solicitudes->first()->user->name : $solicitud->user->name }}
                        </span>
                        <div>
                            <strong></strong>
                            <span class="card-text float-right">
                                {{ \Carbon\Carbon::parse($solicitud->created_at)->formatLocalized('%d de %B, %Y %H:%Mh') }}
                                <span class="small">({{ $solicitud->created_at->diffForHumans() }})</span>
                            </span>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <strong>Modificado por: </strong>
                        <span class="card-text float-right">
                            {{ $solicitud_hasBeenModified ? $solicitud->user->name : ''}}
                        </span>
                        <div>
                            <strong></strong>
                            <span class="card-text float-right">
                                <span class="small">
                                    {{ $solicitud_hasBeenModified ? $solicitud->updated_at->diffForHumans() : '--' }}
                                </span>
                            </span>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <strong>Lote: </strong>
                        <span class="card-text float-right @if(isset($solicitud->lote->num_lote)) text-info @else text-primary @endif">
                            {{ isset($solicitud->lote) ? $solicitud->lote->num_lote : 'Sin lote asignado' }}
                        </span>
                        <div>
                            <strong>Fecha de envío a Mainframe: </strong>
                            <span class="card-text float-right @if(isset($solicitud->lote)) text-info @endif">
                                {{ isset($solicitud->lote) ? \Carbon\Carbon::parse($solicitud->lote->fecha_oficio_lote)->formatLocalized('%d de %B, %Y') : '' }}
                            </span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="text-muted">
            Comentario: {{ isset($solicitud->comment) ? $solicitud->comment : '--' }}
        </div>
        <div class="@if( isset($solicitud->rechazo) && isset($solicitud->final_remark) ) text-danger @else text-primary @endif">
            Observaciones DSPA: {{ isset($solicitud->final_remark) ? $solicitud->final_remark : '--' }}
        </div>
        <div class="text-danger">
            Observaciones Mainframe:
            @if( isset($solicitud->resultado_solicitud) && isset($solicitud->resultado_solicitud->comment) ) {{ $solicitud->resultado_solicitud->comment }} @else -- @endif
        </div>
    </div>

</div>
