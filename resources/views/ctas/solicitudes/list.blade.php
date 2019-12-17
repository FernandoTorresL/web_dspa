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
                <th class="small align-text-top text-sm-center" scope="col">@sortablelink('status_sol_id', 'Estatus')</th>
            </tr>
        </thead>
        <tbody>

        @php
            $var = 0;
        @endphp

        @forelse($solicitudes as $clave_solicitud =>$solicitud)
            {{-- Setting the color row by the result of the solicitud --}}
            @php
                $var += 1;
                $estatus_solicitud = $solicitud->status_sol_id;

                switch($estatus_solicitud)
                {
                    case 1:
                        $color = 'info';
                    break;

                    case 2:
                        $color = 'warning';
                    break;

                    case 3:
                        $color = 'danger';
                    break;

                    case 4:
                        $color = 'secondary';
                    break;

                    case 5:
                        $color = 'primary';
                    break;

                    case 6:
                        $color = 'secondary';
                    break;

                    case 7:
                        $color = 'danger';
                    break;

                    case 8:
                        $color = 'success';
                    break;

                    case 9:
                        $color = 'dark';
                    break;

                    default:
                        $color = 'secondary';
                }
            @endphp

            <tr class="table-{{$color}}">
                <td class="small">
                    <strong>{{ ($solicitudes->currentPage() * $solicitudes->perPage()) + $var - $solicitudes->perPage() }}</strong>
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
                    if ( ( isset($solicitud->valija_oficio) && ($solicitud->valija->delegacion_id <> $solicitud->delegacion->id) ) ) {
                        // If there's 'valija' and valija.delegacion is different to solicitud.delegacion, show also (valija.delegacion)
                        $nombres_delegaciones   = 'Valija(' . $solicitud->valija->delegacion->name .') ';
                        $cifras_delegaciones    = '(' . str_pad($solicitud->valija->delegacion_id, 2, '0', STR_PAD_LEFT) . ') ';
                    }

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
                            {{ isset($solicitud->resultado_solicitud) ? $solicitud->resultado_solicitud->cuenta : $solicitud->cuenta . ' ' }}
                        </button>
                    </a>
                </td>
                <td class="small text-center">{{ $solicitud->movimiento->name }}</td>
                <td class="small">{{ isset($solicitud->grupo1->name) ? $solicitud->grupo1->name : '--' }}</td>
                <td class="small">{{ isset($solicitud->grupo2->name) ? $solicitud->grupo2->name : '--' }}</td>
                <td class="small text-{{$color}} text-center">
                    <a target="_blank" alt="Ver/Editar" href="/ctas/solicitudes/{{ $solicitud->id }}">
                        <button type="button" class="btn btn-outline-{{$color}} btn-sm" data-toggle="tooltip" data-placement="top"
                            title="{{ $solicitud->status_sol->description }}">
                            {{ isset($solicitud->status_sol) ? $solicitud->status_sol->name : 'Algo salió mal. Favor de reportarlo al Administrador' }}
                        </button>
                    </a>
                </td>
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
