@if(count($tabla_movimientos))
    Total de movimientos: {{ $tabla_movimientos->count() }}
    <div class="table table-sm">
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
@endif

@php
    $id_movimiento_anterior = 0;
    $var = 0;
@endphp

            <tbody class="text-monospace">
@forelse($tabla_movimientos as $row_tabla_mov)
    @php
        if ( $row_tabla_mov->mov_id <> $id_movimiento_anterior )
            $var = 0;
        $id_movimiento_anterior = $row_tabla_mov->mov_id;
        $var += 1;
    @endphp
            <tr>
                <th scope="row">{{ $var }}</th>
                {{--<th scope="row">{{ $row_tabla_mov->sol_id.',' }}</th>--}}
                <td class="small">{{ $row_tabla_mov->primer_apellido}}</td>
                <td class="small">{{ $row_tabla_mov->segundo_apellido }}</td>
                <td class="small">{{ $row_tabla_mov->nombre }}</td>
                <td class="small">{{ isset($row_tabla_mov->gpo_a_name) ? $row_tabla_mov->gpo_a_name : '--' }}</td>
                <td class="small">{{ isset($row_tabla_mov->gpo_n_name) ? $row_tabla_mov->gpo_n_name : '--' }}</td>
                <td class="small"><a target="_blank" href="/ctas/solicitudes/{{ $row_tabla_mov->sol_id }}">{{ $row_tabla_mov->cuenta }}</a></td>
                <td class="small">{{ $row_tabla_mov->matricula }}</td>
                <td class="small">{{ $row_tabla_mov->curp }}</td>
                <td class="small"><a target="_blank" href="/ctas/valijas/{{ $row_tabla_mov->val_id }}">{{ $row_tabla_mov->num_oficio_ca }}</a></td>
                <td class="small">{{ $row_tabla_mov->mov_name }}</td>
                <td class="small"><a target="_blank" href="{{ Storage::disk('public')->url($row_tabla_mov->archivo) }}">PDF</a></td>
                <th scope="row">{{ $var }}</th>
            </tr>
@empty
    <p>No hay solicitudes sin lote</p>
@endforelse
            </tbody>

@if(count($tabla_movimientos))
        </table>
    </div>
@endif

{{----Valijas--}}

@if(count($listado_valijas))
    Total de valijas: {{ $listado_valijas->count() }}
    <div class="table table-sm">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">[Delegación solicitante (Delegación origen)- # Oficio Delegación - # Gestión DSPA] (AÑO:YYYY)</th>
            </tr>
            </thead>
@endif

@php
    $var = 0;
@endphp

            <tbody class="text-monospace">
@forelse($listado_valijas as $row_valija)
    @php
        $var += 1;
        $del_valija = str_pad($row_valija->delegacion_id,2,"0",STR_PAD_LEFT);
        $del_sol = str_pad($row_valija->sol_id,2,"0",STR_PAD_LEFT);
    @endphp
            <tr>
                <th scope="row">{{ $var }}</th>
{{--                <th scope="row">{{ $row_valija->id }}</th>--}}
                <td class="small">{{ $row_valija->id.' ('. $del_valija.')  ['.$del_sol.'-'.$row_valija->num_oficio_del.'|'.$row_valija->num_oficio_ca.']' }}</td>
            </tr>
@empty
    <p>No hay valijas</p>
@endforelse
            </tbody>

@if(count($listado_valijas))
        </table>
    </div>
@endif