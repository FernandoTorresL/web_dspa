
    {{--Solicitudes List--}}
    @if(count($solicitudes_rechazadas_con_lote))
        <br>
        <h5 class="text-danger">Total de solicitudes rechazadas en lote: {{ $solicitudes_rechazadas_con_lote->count() }}</h5>

        <div class="table table-hover table-sm">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Primer Apellido</th>
                    <th scope="col">Segundo Apellido</th>
                    <th scope="col">Nombre(s)</th>
                    <th scope="col">Grupo Actual</th>
                    <th scope="col">Grupo Nuevo</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Matrícula</th>
                    <th scope="col">CURP</th>
                    <th scope="col"># de la Valija</th>
                    <th scope="col">Tipo Mov</th>
                    <th scope="col">PDF</th>
                    <th scope="col">Status</th>
                    <th scope="col">#</th>
                </tr>
            </thead>
            <tbody class="text-monospace">
        @php
            $id_movimiento_anterior = NULL;
            $var = 1;
        @endphp
    @endif

    @forelse( $solicitudes_rechazadas_con_lote as $row_tabla_mov )
        @php
            $estatus_solicitud = $row_tabla_mov->status_sol_id;
            // Setting the color row by the result of the solicitud
            switch($estatus_solicitud) {
                case 1:     $color = 'light';       $color_text = 'dark';       break;
                case 2:     $color = 'warning';     $color_text = 'warning';    break;
                case 3:     $color = 'danger';      $color_text = 'danger';     break;
                case 4:     $color = 'secondary';   $color_text = 'secondary';  break;
                case 5:     $color = 'primary';     $color_text = 'primary';    break;
                case 6:     $color = 'info';        $color_text= 'dark';        break;
                case 7:     $color = 'danger';      $color_text = 'danger';     break;
                case 8:     $color = 'success';     $color_text = 'success';    break;
                case 9:     $color = 'secondary';   $color_text = 'secondary';  break;
                default:    $color = 'secondary';
            }
        @endphp

        @if( isset($id_movimiento_anterior) && ($row_tabla_mov->movimiento->id <> $id_movimiento_anterior) )
            @php
                $var = 1;
            @endphp
            <tr>
                <th scope="row"><br></th>
            </tr>
        @endif

            <tr class="table-{{$color}}">
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
                    <a target="_blank" href="/ctas/valijas/{{ isset($row_tabla_mov->valija_oficio) ? $row_tabla_mov->valija_oficio->id : ''}}">
                        {{ isset($row_tabla_mov->valija_oficio) ? $row_tabla_mov->valija_oficio->num_oficio_ca : '--'}}
                    </a>
                </td>
                <td class="small">{{ $row_tabla_mov->movimiento->name }}</td>
                <td class="small">
                    <a target="_blank" href="{{ Storage::disk('public')->url($row_tabla_mov->archivo) }}">
                        PDF
                    </a>
                </td>
                <td class="text-danger text-center">
                    <a target="_blank" alt="Ver/Editar" href="/ctas/solicitudes/{{ $row_tabla_mov->id }}">
                    <button type="button" class="btn btn-outline-{{$color_text}} btn-sm" data-toggle="tooltip" data-placement="top"
                        title="{{ isset($row_tabla_mov->rechazo) ? $row_tabla_mov->rechazo->full_name . '/' . $row_tabla_mov->final_remark : ''}}">
                        {{ isset($row_tabla_mov->status_sol_id) ? $row_tabla_mov->status_sol->name : 'Algo salió mal. Favor de reportarlo al Administrador' }}
                    </button>
                    </a>
                </td>
                <th scope="row">{{ $var }}</th>
            </tr>
        @php
            $id_movimiento_anterior = $row_tabla_mov->movimiento->id;
            $var += 1;
        @endphp
    @empty
        <h5 class="text-warning">
            No hay solicitudes pre-autorizadas 
            @if( isset( $info_lote ) )
                Lote: {{ $info_lote->num_lote }}
            @else
                (Sin lote asignado)
            @endif

        </h5>
    @endforelse
    <tr>
        <th scope="row"><br></th>
    </tr>
    </tbody>

    @if( count($solicitudes_rechazadas_con_lote) )
        </table>
    </div>
    @endif
