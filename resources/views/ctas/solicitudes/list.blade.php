@if(count($list_sol))
    <div class="table">
        <table class="table">
            <tr class="small">
                <th scope="col">@sortablelink('created_at', 'Fecha captura')</th>
                <th scope="col">@sortablelink('primer_apellido', 'Primer Apellido')</th>
                <th scope="col">@sortablelink('segundo_apellido', 'Segundo Apellido')</th>
                <th scope="col">@sortablelink('nombre', 'Nombre(s)')</th>
                <th scope="col">@sortablelink('cuenta', 'Usuario')</th>
                <th scope="col">@sortablelink('movimiento_id', 'Movimiento')</th>
                <th scope="col">Grupo</th>
                <th scope="col">Estatus</th>
            </tr>
@endif

@forelse($list_sol as $solicitud)
    <tr class="small @if(isset($solicitud->rechazo) || (isset($solicitud->resultado_solicitud->rechazo_mainframe))) table-danger @else @if(!isset($solicitud->resultado_solicitud)) table-warning @else table-success @endif @endif">
        @php
            $columna_fecha_usuario = date('d-M-Y', strtotime($solicitud->created_at));
        @endphp
        <td>{{ $columna_fecha_usuario }}</td>
        <td>{{ $solicitud->primer_apellido }}</td>
        <td>{{ $solicitud->segundo_apellido }}</td>
        <td>{{ $solicitud->nombre }}</td>
        <td>
            <a target="_blank" alt="Ver/Editar" href="/ctas/solicitudes/{{ $solicitud->id }}">
                {{ isset($solicitud->rechazo) ? $solicitud->cuenta : (isset($solicitud->resultado_solicitud) ? (isset($solicitud->resultado_solicitud->rechazo_mainframe) ? $solicitud->cuenta : $solicitud->resultado_solicitud->cuenta) : $solicitud->cuenta )  }}
            </a>
        </td>
        <td>{{ $solicitud->movimiento->name }}</td>
        <td>{{ isset($solicitud->gpo_actual) ? $solicitud->gpo_actual->name : '' }} {{ isset($solicitud->gpo_nuevo) ? $solicitud->gpo_nuevo->name : '' }}</td>
        <td class="@if(isset($solicitud->rechazo) || (isset($solicitud->resultado_solicitud->rechazo_mainframe))) text-danger @else @if(isset($solicitud->lote) && (!isset($solicitud->resultado_solicitud))) text-warning @else text-success @endif @endif">
            {{ isset($solicitud->rechazo) ? 'NO PROCEDE' : (isset($solicitud->resultado_solicitud) ? (isset($solicitud->resultado_solicitud->rechazo_mainframe) ? 'NO PROCEDE' : 'ATENDIDA') : 'EN ESPERA DE RESPUESTA' ) }}</td>
        {{--<td class="small">
            <small>
                Capturado por: {{ $solicitud->user->name }}

                {{ $solicitud->created_at->diffForHumans() }}
            </small>
        </td>--}}
    </tr>
@empty
    <p>No hay solicitudes recientes</p>
@endforelse

@if(count($list_sol))
        </table>
    </div>

    <div class="mt-2 mx-auto justify-content-center">
        {!! $list_sol->appends(\Request::except('page'))->render() !!}
    </div>
@endif
