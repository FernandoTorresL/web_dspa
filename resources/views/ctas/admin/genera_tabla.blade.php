
    {{--Solicitudes List--}}
    @if(count($tabla_movimientos))
        <br>
        <h5 class="text-info">Total de pre-autorizadas listas para enviar a Mainframe: {{ $tabla_movimientos->count() }}</h5>

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
                    <th scope="col">#</th>
                </tr>
            </thead>
            <tbody class="text-monospace">
        @php
            $id_movimiento_anterior = NULL;
            $var = 1;
        @endphp
    @endif

    @forelse( $tabla_movimientos as $row_tabla_mov )
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

        @if( isset($id_movimiento_anterior) && ($row_tabla_mov->mov_id <> $id_movimiento_anterior) )
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
                <td class="small">{{ isset($row_tabla_mov->gpo_a_name) ? $row_tabla_mov->gpo_a_name : '--' }}</td>
                <td class="small">{{ isset($row_tabla_mov->gpo_n_name) ? $row_tabla_mov->gpo_n_name : '--' }}</td>
                <td class="small">
                    <a target="_blank" href="/ctas/solicitudes/{{ $row_tabla_mov->sol_id }}">
                        {{ $row_tabla_mov->cuenta }}
                    </a>
                </td>
                <td class="small">{{ $row_tabla_mov->matricula }}</td>
                <td class="small">{{ $row_tabla_mov->curp }}</td>
                <td class="small">
                    <a target="_blank" href="/ctas/valijas/{{ $row_tabla_mov->val_id }}">
                        {{ $row_tabla_mov->num_oficio_ca }}
                    </a>
                </td>
                <td class="small">{{ $row_tabla_mov->mov_name }}</td>
                <td class="small">
                    <a target="_blank" href="{{ Storage::disk('public')->url($row_tabla_mov->archivo) }}">
                        PDF
                    </a>
                </td>
                <th scope="row">{{ $var }}</th>
            </tr>
        @php
            $id_movimiento_anterior = $row_tabla_mov->mov_id;
            $var += 1;
        @endphp
    @empty
        <h5 class="text-danger">
            No hay solicitudes pre-autorizadas para tramitar
            @if( isset( $info_lote ) )
            <p>Lote: {{ $info_lote->num_lote }} id: {{ $info_lote->id }}</p>
            @else
                (Sin lote asignado)
            @endif

        </h5>
        <br>
    @endforelse
    <tr>
        <th scope="row"><br></th>
    </tr>
    </tbody>

    @if( count($tabla_movimientos) )
        </table>
    </div>
    @endif
