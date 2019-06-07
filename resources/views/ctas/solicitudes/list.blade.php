@php
    use Carbon\Carbon;
    setlocale(LC_TIME, 'es-ES');
    \Carbon\Carbon::setUtf8(false);
@endphp

<div class="container">
    <h5 class="text-info">Solicitudes localizadas: {{ $solicitudes->total() }} </h5>
    <div class="row" align="center">
        <div class="mt-2 mx-auto justify-content-center">
            {!! $solicitudes->appends(\Request::except('page'))->render() !!}
        </div>
    </div>
</div>

<div class="table table-hover table-sm">
    <table class="table">
        <thead class="thead-info">
            <tr>
                <th class="small align-text-top" scope="col">@sortablelink('created_at', 'Fecha captura')</th>
                <th class="small align-text-top" scope="col">@sortablelink('lote_id', 'Lote')</th>
                <th class="small align-text-top" scope="col">@sortablelink('valija_oficio.num_oficio_del', 'Oficio')</th>
                <th class="small align-text-top" scope="col">@sortablelink('delegacion_id', 'Delegación')</th>
                <th class="small align-text-top" scope="col">@sortablelink('subdelegacion_id', 'Subdelegación')</th>
                <th class="small align-text-top" scope="col">@sortablelink('primer_apellido', 'Primer apellido')</th>
                <th class="small align-text-top" scope="col">@sortablelink('segundo_apellido', 'Segundo apellido')</th>
                <th class="small align-text-top text-sm-left" scope="col">@sortablelink('nombre', 'Nombre(s)')</th>
                <th class="small align-text-top" scope="col">@sortablelink('cuenta', 'Usuario')</th>
                <th class="small align-text-top" scope="col">@sortablelink('movimiento_id', 'Movimiento')</th>
                <th class="small align-text-top" scope="col">@sortablelink('grupo1.name', 'Gpo actual')</th>
                <th class="small align-text-top" scope="col">@sortablelink('grupo2.name', 'Gpo nuevo')</th>
                <th class="small align-text-top text-sm-center" scope="col">@sortablelink('rechazo_id', 'Rechazo CA')</th>
                <th class="small align-text-top text-sm-center" scope="col">@sortablelink('resultado_solicitud.rechazo_mainframe_id', 'Rechazo Mainframe')</th>
            </tr>
        </thead>
        <tbody>


@forelse($solicitudes as $clave_solicitud =>$solicitud)

    {{-- Setting the color row by the result of the solicitud --}}
    @if( isset($solicitud->rechazo) || isset($solicitud->resultado_solicitud->rechazo_mainframe) )
            {{-- Solicitud was denny... --}}
            <tr class="table-danger">
    @else
        @if( !isset($solicitud->resultado_solicitud) )
            {{-- There's not response for the solicitud --}}
            @if( isset($solicitud->lote) )
                {{-- This solicitud has a lote and we're waiting for response --}}
                <tr class="table-warning">
            @else
                {{-- We're analizing your solicitud --}}
                <tr class="table-light">
            @endif
        @else
            {{-- There's an OK response for the solicitud --}}
            <tr class="table-success">
        @endif
    @endif

        <td class="small text-left">{{ $solicitud->created_at->diffForHumans() }}</td>
        <td class="small">{{ isset($solicitud->lote) ? ( $solicitud->lote->id<>408 ? $solicitud->lote->num_lote : '--' ) : '--' }}</td>
        <td class="small">{{ isset($solicitud->valija_oficio) ? $solicitud->valija_oficio->num_oficio_del : '--' }}</td>
        <td class="small" colspan="2">{{ isset($solicitud->valija_oficio) ? $solicitud->valija->delegacion_id : '' }}({{ $solicitud->delegacion->id }}){{ $solicitud->delegacion->name }} - ({{ $solicitud->subdelegacion->num_sub }}){{ $solicitud->subdelegacion->name }}</td>
        <td class="small" colspan="3">{{ $solicitud->primer_apellido }}-{{ $solicitud->segundo_apellido }}-{{ $solicitud->nombre }}</td>
        <td class="small">
            <a target="_blank" alt="Ver/Editar" href="/ctas/solicitudes/{{ $solicitud->id }}">
                <button type="button" class="btn btn-primary btn-sm text-monospace" data-toggle="tooltip" data-placement="right"
                        title="Click para ver detalle...">
                    @if( isset($solicitud->resultado_solicitud) )
                        {{--If solicitud has a response ... --}}
                        {{ $solicitud->resultado_solicitud->cuenta }}
                    @else
                        {{--  ...show the captured value --}}
                        {{ str_pad($solicitud->cuenta, 7, ' ', STR_PAD_RIGHT) }}
                    @endif
                </button>
            </a>
        </td>
        <td class="small text-center">{{ $solicitud->movimiento->name }}</td>
        <td class="small">{{ isset($solicitud->grupo1->name) ? $solicitud->grupo1->name : '--' }}</td>
        <td class="small">{{ isset($solicitud->grupo2->name) ? $solicitud->grupo2->name : '--' }}</td>

        {{-- Setting the solicitud status --}}
        @if( isset($solicitud->rechazo) || isset($solicitud->resultado_solicitud->rechazo_mainframe) )
            {{-- Solicitud was denny... --}}
            <td class="small text-danger text-center" colspan="2">
                <a target="_blank" alt="Ver/Editar" href="/ctas/solicitudes/{{ $solicitud->id }}">
                        @if( ( isset($solicitud->resultado_solicitud->status) ? $solicitud->resultado_solicitud->status : 0 ) == 1 )
                            {{-- There's an response, but we have to send again the solicitud --}}
                            <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="left"
                                    title="{{ isset($solicitud->rechazo) ? $solicitud->rechazo->full_name .'/' : (isset($solicitud->resultado_solicitud) ? ( isset($solicitud->resultado_solicitud->rechazo_mainframe) ? $solicitud->resultado_solicitud->rechazo_mainframe->name . ':' . $solicitud->resultado_solicitud->comment : '' ) : '') }} {{ isset($solicitud->final_remark) ? $solicitud->final_remark : '' }}. Se reintentará en otro lote">
                                No procede. Pendiente
                            </button>
                        @else
                            <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top"
                                    title="{{ isset($solicitud->rechazo) ? $solicitud->rechazo->full_name .'/' : (isset($solicitud->resultado_solicitud) ? ( isset($solicitud->resultado_solicitud->rechazo_mainframe) ? $solicitud->resultado_solicitud->rechazo_mainframe->name . ':' . $solicitud->resultado_solicitud->comment : '' ) : '') }} {{ isset($solicitud->final_remark) ? $solicitud->final_remark : '' }}">
                                  No procede
                            </button>
                        @endif
                </a>
            </td>
        @else
            @if( !isset($solicitud->resultado_solicitud) )
                {{-- There's not response for the solicitud --}}
                @if( isset($solicitud->lote) )
                        <td class="small text-warning text-center" colspan="2">
                            <a target="_blank" alt="Ver/Editar" href="/ctas/solicitudes/{{ $solicitud->id }}">
                    {{-- This solicitud has a lote and we're waiting for response --}}
                    @if( $solicitud->lote->id <> 408 )
                                <button type="button" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top"
                                        title="Aún en trámite en área de Mainframe. En espera de respuesta">
                                    En Mainframe
                                </button>
                    @else
                                <button type="button" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top"
                                        title="Se requiere VoBo de Vigencia de Derechos. En espera de respuesta">
                                    En espera de autorización
                                </button>
                    @endif
                            </a>
                        </td>
                @else
                    {{-- We're analizing your solicitud --}}
                    <td class="small text-dark text-center" colspan="2">
                        <a target="_blank" alt="Ver/Editar" href="/ctas/solicitudes/{{ $solicitud->id }}">
                            <button type="button" class="btn btn-outline-dark btn-sm" data-toggle="tooltip" data-placement="top"
                                    title="En revisión por personal de Nivel Central">
                                En revisión
                            </button>
                        </a>
                    </td>
                @endif
            @else
                <td class="small text-success text-center" colspan="2">
                    <a target="_blank" alt="Ver/Editar" href="/ctas/solicitudes/{{ $solicitud->id }}">
                        <button type="button" class="btn align-content-center btn-outline-success btn-sm" data-toggle="tooltip" data-placement="left"
                                title="Solicitud operada por Mainframe">
                            Atendida
                        </button>
                    </a>
                </td>
            @endif
        @endif
    </tr>
    </tbody>
@empty
    <p>No hay solicitudes que coincidan con el criterio de búsqueda</p>
@endforelse

    </table>
</div>

<div class="row" align="center">
       <div class="mt-2 mx-auto justify-content-center">
        {!! $solicitudes->appends(\Request::except('page'))->render() !!}
    </div>
</div>
