@if(count($solicitudes_sin_lote))
    <div class="table table-sm">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Origen</th>
                <th scope="col">Delegación</th>
                <th scope="col">Tipo Movimiento</th>
                <th scope="col"># Solicitudes</th>
            </tr>
            </thead>
@endif

@php
    $var = 0;
@endphp

@forelse($solicitudes_sin_lote as $solicitud_sin_lote)
    @php
        $var += 1;
    @endphp
            <tbody>
            <tr>
                <th scope="row">{{ $var }}</th>
                <td class="small">{{ $solicitud_sin_lote->origen_id}}</td>
                <td class="small">{{ $solicitud_sin_lote->delegacion_id }}</td>
                <td class="small">{{ $solicitud_sin_lote->name }}</td>
                <td class="small">{{ $solicitud_sin_lote->total_solicitudes }}</td>
            </tr>
            </tbody>
@empty
    <p>No hay solicitudes sin lote</p>
@endforelse

@if(count($solicitudes_sin_lote))
        </table>
    </div>
@endif

<br>
<br>
@if(count($listado_lotes))
    <div class="table table-sm">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col"># Lote</th>
                    <th scope="col">Oficio CA</th>
                    <th scope="col">Fecha oficio</th>
                    <th scope="col"># Ticket MSI</th>
                    <th scope="col">Fecha de atención</th>
                    <th scope="col">Cantidad de solicitudes</th>
                    <th scope="col">Comentario</th>
                </tr>
            </thead>
@endif

@forelse($listado_lotes as $lote)
    <tbody>
    <tr>
        <td class="small">{{ $lote->num_lote}}</td>
        <td class="small">{{ $lote->num_oficio_ca }}</td>
        <td class="small">{{ $lote->fecha_oficio_lote }}</td>
        <td class="small">{{ $lote->ticket_msi }}</td>
        <td class="small">{{ $lote->attended_at }}</td>
        <td class="small">{{ $lote->total_solicitudes }}</td>
        <td class="small">{{ $lote->comment }}</td>
    </tr>
    </tbody>
@empty
    <p>No hay lotes registrados</p>
@endforelse

@if(count($listado_lotes))
        </table>
    </div>
@endif

<br>
<br>
@if(count($solicitudes_sin_lote2))
    <div class="table table-sm">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Num Oficio CA</th>
                <th scope="col">Fecha de Captura / Fecha de Modificación</th>
                <th scope="col">Última modificación por</th>
                <th scope="col">Delegación - Subdelegación</th>
                <th scope="col">Nombre completo</th>
                <th scope="col">Usuario (Mov)</th>
                <th scope="col">Grupo Actual->Nuevo</th>
                <th scope="col">Causa Rechazo</th>
                <th scope="col">Estatus</th>
                <th scope="col">Comentarios</th>
                <th scope="col">PDF</th>
            </tr>
            </thead>
            @endif

            @php
                $var = 0;
            @endphp

            @forelse($solicitudes_sin_lote2 as $solicitud)
                @php
                    $var += 1;
                @endphp
                <tbody>
                <tr>
                    <th scope="row">{{ $var }}</th>
                    @php
                        if ( !empty( $solicitud->valija ) )
                            $archivoPDF = '<a href="' . $solicitud->valija->name . '"  target="_new">PDF</a>';
                        else
                            $archivoPDF = '(Sin PDF)';
                    @endphp
                    <td class="small"><a target="_blank" href="/ctas/valijas/{{ $solicitud->valija->id }}">{{ $solicitud->valija->num_oficio_ca }} </a></td>

                    @php
                        $columna_fecha_usuario = $solicitud->created_at;
                        $columna_fecha_usuario2 = '';
                    if ( $solicitud->created_at == $solicitud->updated_at )
                        $columna_fecha_usuario2 = '';
                    else
                        $columna_fecha_usuario2 = $solicitud->updated_at;

                    @endphp
                    <td class="small">{{ $columna_fecha_usuario }} <br> {{ $columna_fecha_usuario2 }} </td>
                    <td class="small">{{ $solicitud->user->name }}</td>
                    <td class="small">{{ $solicitud->valija->delegacion_id }}({{ $solicitud->delegacion->id }}){{ $solicitud->delegacion->name }} - ({{ $solicitud->subdelegacion->num_sub }}){{ $solicitud->subdelegacion->name }}</td>
                    <td class="small">{{ $solicitud->primer_apellido }}-{{ $solicitud->segundo_apellido }}-{{ $solicitud->nombre }}</td>
                    <td class="small"><a target="_blank" alt="Ver/Editar" href="/ctas/solicitudes/{{ $solicitud->id }}">{{ $solicitud->cuenta }} ({{ $solicitud->movimiento->name }})</a></td>
                    <td class="small">{{ isset($solicitud->gpo_actual) ? $solicitud->gpo_actual->name : '' }} -> {{ isset($solicitud->gpo_nuevo) ? $solicitud->gpo_nuevo->name : '' }}</td>
                    <td class="small">{{ isset($solicitud->rechazo) ? $solicitud->rechazo->full_name : '-' }}</td>
                    <td class="small">{{ isset($solicitud->rechazo) ? 'Rechazada' : 'OK' }}</td>
                    <td class="small">{{ $solicitud->comment }}</td>
                    <td class="small"><a target="_blank" href="{{ $solicitud->archivo }}">{{$solicitud->id}}-PDF</a></td>
                </tr>
                </tbody>
            @empty
                <p>No hay solicitudes sin lote</p>
            @endforelse

            @if(count($solicitudes_sin_lote2))
        </table>
    </div>
@endif
