@php
    use App\Http\Helpers\Helpers;

    $var = 0;
@endphp

<div class="table table-hover table-sm">
    <table class="table">
        <thead class="small">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Fecha captura</th>
                <th scope="col">Subdelegación </th>
                <th scope="col">Apellidos-Nombre</th>
                <th scope="col">Matrícula</th>
                <th scope="col">Usuario</th>
                <th>Tipo Mov</th>
                <th>Gpo actual</th>
                <th>Gpo nuevo</th>
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

                $color_solicitud        = $tmp_array['color_solicitud'];
                $color_text_solicitud   = $tmp_array['color_text_solicitud'];
                $possible_status_sol    = $tmp_array['possibles_status_sol'];
                $var += 1;
                $estatus_solicitud = $solicitud->status_sol_id;
            @endphp

            <tr class="table-{{ $color_solicitud }}">
                <td class="small">
                    <strong>{{ ($solicitudes->currentPage() * $solicitudes->perPage()) + $var - $solicitudes->perPage() }}</strong>
                </td>

                <td class="small text-left">
                    {{ Helpers::format_datetime_short3($solicitud->created_at) }}
                </td>

                <td class="small">
                    {{ $solicitud->subdelegacion->name }}
                </td>

                <td class="small">{{ $solicitud->primer_apellido }}-{{ $solicitud->segundo_apellido }}-{{ $solicitud->nombre }}</td>

                <td class="small text-left">{{ $solicitud->matricula }}</td>

                <td class="small text-left">
                    <a target="_blank" alt="Ver/Editar" href="/ctas/solicitudes/{{ $solicitud->id }}">
                        <button type="button" class="btn btn-primary btn-sm text-monospace" data-toggle="tooltip"
                            title="Click para ver detalle...">
                                {{ isset($solicitud->resultado_solicitud) ? $solicitud->resultado_solicitud->cuenta : $solicitud->cuenta . '    ' }}
                        </button>
                    </a>
                </td>

                <td class="small">{{ $solicitud->movimiento->name }}</td>

                <td class="small">{{ isset($solicitud->gpo_actual) ? $solicitud->gpo_actual->name : '' }}</td>

                <td class="small">{{ isset($solicitud->gpo_nuevo) ? $solicitud->gpo_nuevo->name : '' }}</td>

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
            <p>No hay solicitudes que coincidan con el criterio de búsqueda</p>
        @endforelse
        </tbody>
    </table>
</div>

<div class="row mt-2 mx-auto justify-content-center">
    {!! $solicitudes->appends(\Request::except('page'))->render() !!}
</div>
