@php
    use App\Http\Helpers\Helpers;

    $var = 0;
@endphp

<div>
    <div class="row mt-2 mx-auto justify-content-center">
        {!! $solicitudes->appends(\Request::except('page'))->render() !!}
    </div>
    
    <p class="text-primary">
        Solicitudes localizadas: {{ $solicitudes->total() }}
    </p>
    <table class="table">
        <thead class="small">
            <tr>
                <th>#</th>
                <th>Fecha captura</th>
                <th>Lote</th>
                <th>Delegación/Subdelegación</th>
                <th>Apellidos-Nombre</th>
                <th class="text-center">CURP (Matrícula)</th>
                <th class="text-center">Usuario</th>
                <th class="text-center">Tipo mov</th>
                <th class="text-center">Gpo actual</th>
                <th class="text-center">Gpo nuevo</th>
                <th class="text-center" scope="col">Estatus</th>
            </tr>
        </thead>
        <tbody>

        @forelse($solicitudes as $clave_solicitud =>$solicitud)
            @php
                // If solicitud has a response... show the captured value
                $cuenta = $solicitud->cuenta;
                if ( isset($solicitud->resultado_solicitud) )
                    $cuenta = $solicitud->resultado_solicitud->cuenta;

                $tmp_array = Helpers::set_status_sol_flow($solicitud->status_sol_id);
                //dd($solicitud);
                $color_solicitud        = $tmp_array['color_solicitud'];
                $color_text_solicitud   = $tmp_array['color_text_solicitud'];
                $possible_status_sol    = $tmp_array['possibles_status_sol'];
                $var += 1;
                $estatus_solicitud = $solicitud->status_sol_id;
            @endphp

            <tr class="table-{{ $color_solicitud }} text-monospace">
                <td class="small">
                    <strong>{{ ($solicitudes->currentPage() * $solicitudes->perPage()) + $var - $solicitudes->perPage() }}</strong>
                </td>

                <td class="small text-left">
                    {{ Helpers::format_datetime_short($solicitud->created_at) }}
                </td>

                <td class="small text-left">
                    {{ isset($solicitud->lote_id) ? $solicitud->lote->num_lote : '' }}
                </td>

                <td class="small">
                    {{ str_pad($solicitud->delegacion_id, 2, '0', STR_PAD_LEFT) . '-' .
                    $solicitud->delegacion->name }}
                    <p>
                        {{ $solicitud->subdelegacion->num_sub == 0 ?
                        '' :
                        str_pad($solicitud->subdelegacion->num_sub, 2, '0', STR_PAD_LEFT) . '-' .
                        $solicitud->subdelegacion->name }}
                    </p>
                </td>

                <td class="small">{{ $solicitud->primer_apellido }}-{{ $solicitud->segundo_apellido }}-{{ $solicitud->nombre }}</td>

                <td class="small text-left">
                    {{ $solicitud->curp }}
                    <p>
                        ({{ $solicitud->matricula }})
                    </p>
                </td>

                <td class="small text-left">
                    <a target="_blank" alt="Ver/Editar" href="/ctas/solicitudes/{{ $solicitud->id }}">
                        <button type="button" class="btn btn-primary btn-sm text-monospace" data-toggle="tooltip"
                            title="Click para ver detalle...">
                                {{ isset($solicitud->resultado_solicitud) ? $solicitud->resultado_solicitud->cuenta : $solicitud->cuenta . '    ' }}
                        </button>
                    </a>
                </td>

                <td class="small text-center">{{ $solicitud->movimiento->name }}</td>

                <td class="small text-center">{{ isset($solicitud->gpo_actual) ? $solicitud->gpo_actual->name : '' }}</td>

                <td class="small text-center">{{ isset($solicitud->gpo_nuevo) ? $solicitud->gpo_nuevo->name : '' }}</td>

                <td class="small text-{{ $color_solicitud }} text-center">
                    <a target="_blank" alt="Ver/Editar" href="/ctas/solicitudes/{{ $solicitud->id }}">
                        <button type="button" class="btn btn-outline-{{ $color_text_solicitud }} btn-sm" data-toggle="tooltip" data-placement="top"
                            title="{{ $solicitud->status_sol->description }}">
                            {{ isset($solicitud->status_sol) ? $solicitud->status_sol->name : 'Algo salió mal. Favor de reportarlo al Administrador' }}
                        </button>
                    </a>
                </td>
            </tr>

        @empty
            <p class="text-danger">No hay solicitudes que coincidan con el criterio de búsqueda</p>
        @endforelse
        </tbody>
    </table>
</div>

<div class="row mt-2 mx-auto justify-content-center">
    {!! $solicitudes->appends(\Request::except('page'))->render() !!}
</div>
