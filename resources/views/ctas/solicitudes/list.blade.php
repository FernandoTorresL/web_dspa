@php
    use Carbon\Carbon;
    setlocale(LC_TIME, 'es-ES');
    \Carbon\Carbon::setUtf8(false);
@endphp

<div class="container">
    <h5 class="text-primary">Solicitudes localizadas: {{ $solicitudes->total() }} </h5>
    <div class="row" align="center">
        <div class="mt-2 mx-auto justify-content-center">
            {!! $solicitudes->appends(\Request::except('page'))->render() !!}
        </div>
    </div>
</div>

<div class="table table-hover table-sm">
    <table class="table">
        <thead class="thead-primary">
            <tr>
                <th class="small align-text-top" scope="col">#</th>
                <th class="small align-text-top" scope="col">@sortablelink('created_at', 'Fecha captura')</th>
                <th class="small align-text-top" scope="col">@sortablelink('lote_id', 'Lote')</th>
                <th class="small align-text-top" scope="col">@sortablelink('valija_oficio.num_oficio_del', 'Oficio Del (#Gestión CA)')</th>
                <th class="small align-text-top" scope="col">@sortablelink('delegacion_id', '#Del')</th>
                <th class="small align-text-top" scope="col">@sortablelink('subdelegacion_id', '#Subdel')</th>
                <th class="small align-text-top" scope="col">@sortablelink('primer_apellido', 'Primer apellido')</th>
                <th class="small align-text-top" scope="col">@sortablelink('segundo_apellido', 'Segundo apellido')</th>
                <th class="small align-text-top text-sm-left" scope="col">@sortablelink('nombre', 'Nombre(s)')</th>
                <th class="small align-text-top text-sm-right" scope="col">@sortablelink('curp', 'CURP -')</th>
                <th class="small align-text-top" scope="col">@sortablelink('matrícula', '(Matrícula)')</th>
                <th class="small align-text-top" scope="col">@sortablelink('cuenta', 'Usuario')</th>
                <th class="small align-text-top" scope="col">@sortablelink('movimiento_id', 'Movimiento')</th>
                <th class="small align-text-top" scope="col">@sortablelink('grupo1.name', 'Gpo actual')</th>
                <th class="small align-text-top" scope="col">@sortablelink('grupo2.name', 'Gpo nuevo')</th>
                <th class="small align-text-top text-sm-center" scope="col">@sortablelink('rechazo_id', 'Rechazo CA')</th>
                <th class="small align-text-top text-sm-center" scope="col">@sortablelink('resultado_solicitud.rechazo_mainframe_id', 'Rechazo Mainframe')</th>
            </tr>
        </thead>
        <tbody>

        @php
            $var = 0;
        @endphp

        @forelse($solicitudes as $clave_solicitud =>$solicitud)
            @php
                $var += 1;
            @endphp

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

                <td class="small">
                    <strong>
                    {{ ($solicitudes->currentPage() * $solicitudes->perPage()) + $var - $solicitudes->perPage() }}
                    </strong>
                </td>
                <td class="small text-left">
                        <span>{{ $solicitud->created_at->format('dMy') }}</span>
                        <span>{{ $solicitud->created_at->format('H:i') }}</span>
                </td>
                <td class="small text-left">
                        @if( isset($solicitud->lote) && ($solicitud->lote->id <> env('LOTE_CCEVyD') ) )
                            <a target="_blank" title="Ir a detalle solicitud" href="/ctas/solicitudes/{{ $solicitud->id }}" data-placement="center" class="badge badge-primary">
                                {{ $solicitud->lote->num_lote }}
                            </a>
                        @else
                            {{ '--' }}
                        @endif
                </td>
                <td class="small">
                    @if( isset($solicitud->valija_oficio) )
                        <a target="_blank" title="{{ $solicitud->valija_oficio->num_oficio_ca }}" href="/ctas/valijas/{{ $solicitud->valija_id }}" data-placement="center" class="badge badge-primary">
                            {{ $solicitud->valija_oficio->num_oficio_del }} ({{ $solicitud->valija_oficio->num_oficio_ca }})

                        </a>

                    @else
                        {{ '--' }}
                    @endif
                </td>
                <td class="small text-center" colspan="2">
                    @php
                        $nombres_delegaciones = $cifras_delegaciones = null;
            @endphp
            @if( ( isset($solicitud->valija_oficio) && ($solicitud->valija->delegacion_id <> $solicitud->delegacion->id) ) )
                {{-- If there's 'valija' and valija.delegacion is different to solicitud.delegacion, show also (valija.delegacion) --}}
                @php
                    $nombres_delegaciones   = 'Valija(' . $solicitud->valija->delegacion->name .') ';
                    $cifras_delegaciones    = '(' . str_pad($solicitud->valija->delegacion_id, 2, '0', STR_PAD_LEFT) . ') ';
                @endphp
            @endif
            @php
                $nombres_delegaciones .= $solicitud->delegacion->name . ' - ' . $solicitud->subdelegacion->name;
                $cifras_delegaciones .= str_pad($solicitud->delegacion->id, 2, '0', STR_PAD_LEFT) . ' - ' . str_pad($solicitud->subdelegacion->num_sub, 2, '0', STR_PAD_LEFT);
            @endphp
            <a target="_blank" data-toggle="tooltip" data-placement="center"
               title="{{ $nombres_delegaciones }}" href="/ctas/solicitudes/{{ $solicitud->id }}" class="badge badge-primary">
                {{ $cifras_delegaciones }}
            </a>
        </td>
        <td class="small" colspan="3">{{ $solicitud->primer_apellido }}-{{ $solicitud->segundo_apellido }}-{{ $solicitud->nombre }}</td>
        <td class="small text-center"  colspan="2">{{ $solicitud->curp }} - ({{ $solicitud->matricula }})</td>
        <td class="small">
            <a target="_blank" alt="Ver/Editar" href="/ctas/solicitudes/{{ $solicitud->id }}">
                <button type="button" class="btn btn-primary btn-sm text-monospace" data-toggle="tooltip" data-placement="right"
                        title="Click para ver detalle...">
                    @if( isset($solicitud->resultado_solicitud) )
                        {{--If solicitud has a response ... --}}
                        {{ $solicitud->resultado_solicitud->cuenta }}
                    @else
                        {{--  ...show the captured value --}}
                        {{ $solicitud->cuenta . ' ' }}
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
