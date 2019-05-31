
    {{--Rejections list--}}
    @if( count($listado_mov_rechazados) )
        <br>
        <h5 class="text-danger">Total de movimientos rechazados: {{ $listado_mov_rechazados->count('') }}</h5>
        <div class="table table-hover table-sm">
            <table class="table">
                <thead>
                <tr>
                    <th class="align-text-top" scope="col">#</th>
                    <th class="align-text-top" scope="col">Primer Apellido</th>
                    <th class="align-text-top" scope="col">Segundo Apellido</th>
                    <th class="align-text-top" scope="col">Nombre(s)</th>
                    <th class="align-text-top" scope="col">Gpo Actual</th>
                    <th class="align-text-top" scope="col">Gpo Nuevo</th>
                    <th class="align-text-top" scope="col">Usuario</th>
                    <th class="align-text-top" scope="col">Matrícula</th>
                    <th class="align-text-top" scope="col">CURP</th>
                    <th class="align-text-top" scope="col">Valija</th>
                    <th class="align-text-top" scope="col">Tipo Mov</th>
                    <th class="align-text-top" scope="col">PDF</th>
                    <th class="align-text-top" scope="col">Status</th>
                </tr>
            </thead>
            <tbody class="text-monospace">
        @php
            $id_movimiento_anterior = NULL;
            $var = 1;
         @endphp
    @endif

    @forelse( $listado_mov_rechazados as $row_tabla_mov )
        @if( isset($id_movimiento_anterior) && ($row_tabla_mov->movimiento_id <> $id_movimiento_anterior) )
            @php
                $var = 1;
            @endphp
            <tr>
                <th scope="row"><br></th>
            </tr>
        @endif

        <tr class="table-danger">
            <th scope="row">{{ $var }}</th>
            <td class="small">{{ $row_tabla_mov->primer_apellido}}</td>
            <td class="small">{{ $row_tabla_mov->segundo_apellido }}</td>
            <td class="small">{{ $row_tabla_mov->nombre }}</td>
            <td class="small">{{ isset($row_tabla_mov->gpo_actual) ? $row_tabla_mov->gpo_actual->name : '--' }}</td>
            <td class="small">{{ isset($row_tabla_mov->gpo_nuevo) ? $row_tabla_mov->gpo_nuevo->name : '--' }}</td>
            <td class="small">
                <a target="_blank" href="/ctas/solicitudes/{{ $row_tabla_mov->id }}">
                    {{ $row_tabla_mov->cuenta }}
                </a>
            </td>
            <td class="small">{{ $row_tabla_mov->matricula }}</td>
            <td class="small">{{ $row_tabla_mov->curp }}</td>
            <td class="small">
                <a target="_blank" href="/ctas/valijas/{{ $row_tabla_mov->valija_id }}">
                    {{ isset($row_tabla_mov->valija) ? $row_tabla_mov->valija->num_oficio_ca : '--' }}
                </a>
            </td>
            <td class="small">{{ $row_tabla_mov->movimiento->name }}</td>
            <td class="small">
                <a target="_blank" href="{{ Storage::disk('public')->url($row_tabla_mov->archivo) }}">
                    PDF
                </a>
            </td>
            {{-- Setting the solicitud status --}}
            {{-- Solicitud was denny by DSPA... --}}
            <td class="text-danger text-center">
                <a target="_blank" alt="Ver/Editar" href="/ctas/solicitudes/{{ $row_tabla_mov->id }}">
                <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top"
                        title="{{ $row_tabla_mov->rechazo->full_name . '/' . $row_tabla_mov->final_remark }}">
                    No procede
                </button>
                </a>
            </td>
        </tr>
        @php
            $id_movimiento_anterior = $row_tabla_mov->movimiento_id;
            $var += 1;
        @endphp
    @empty
        <h5 class="text-danger">No hay solicitudes rechazadas</h5>
        <br>
    @endforelse
    </tbody>

    @if(count($listado_mov_rechazados))
        </table>
    </div>
    @endif
