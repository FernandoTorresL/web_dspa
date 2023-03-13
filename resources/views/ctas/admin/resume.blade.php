@php
    use App\Http\Helpers\Helpers;

    $dia = $del = NULL;
@endphp


<div class="col-7">
    <table class="table table-sm table-striped">
        <thead class="small">
            <tr>
                <th class="text-left">Fecha de creación</th>
                <th class="text-center">Movimiento</th>
                <th class="text-left">Estatus</th>
                <th class="text-right"># solicitudes</th>
            </tr>
        </thead>
        <tbody class="text-monospace">
@forelse($solicitudes_sin_lote_por_fecha_mov_status as $solicitud_sin_lote_por_fecha_mov)
    @php
        $tmp_array = Helpers::set_status_sol_flow($solicitud_sin_lote_por_fecha_mov->status_sol_id);
        $color_solicitud        = $tmp_array['color_solicitud'];
        $color_text_solicitud   = $tmp_array['color_text_solicitud'];
    @endphp

            <tr>
                <th class="small">
    @if( $dia <> $solicitud_sin_lote_por_fecha_mov->Dia_creacion )
                    <strong>{{$solicitud_sin_lote_por_fecha_mov->Dia_creacion}}</strong>
        @php
            $dia = $solicitud_sin_lote_por_fecha_mov->Dia_creacion
        @endphp
    @endif
                </th>

                <td class="small text-center">
                    {{ $solicitud_sin_lote_por_fecha_mov->movimiento->name }}
                </td>

                <td class="small text-left table-{{ $color_solicitud }} text-{{ $color_text_solicitud }}">
                    {{ $solicitud_sin_lote_por_fecha_mov->status_sol->name }}
                </td>

                <td class="small text-right">
                    {{ $solicitud_sin_lote_por_fecha_mov->total_solicitudes }}
                </td>
            </tr>
@empty
            <tr>
                <td>No hay solicitudes sin lote</td>
            </tr>
@endforelse

        </tbody>
    </table>
</div>


<div class="col-3">
    <table class="table table-sm table-striped">
        <thead class="small">
            <tr>
                <th class="text-center">Movimiento</th>
                <th class="text-right"># solicitudes</th>
            </tr>
        </thead>
        <tbody class="text-monospace">
@forelse($solicitudes_sin_lote_por_mov as $solicitud_sin_lote_por_mov)
            <tr>
                <th class="small text-center"><strong>{{ $solicitud_sin_lote_por_mov->movimiento->name }}</strong></th>
                <td class="small text-right">{{ $solicitud_sin_lote_por_mov->total_solicitudes }}</td>
            </tr>
@empty
            <tr>
                <td>No hay solicitudes sin lote</td>
            </tr>
@endforelse
        </tbody>
    </table>
</div>


<div class="col-5">
    <table class="table table-sm table-striped">
        <thead class="small">
            <tr>
                <th class="text-left">Estatus</th>
                <th class="text-right"># solicitudes</th>
            </tr>
        </thead>
        <tbody class="text-monospace">
@forelse($solicitudes_sin_lote_por_estatus as $solicitud_sin_lote_por_estatus)
    @php
        $tmp_array = Helpers::set_status_sol_flow($solicitud_sin_lote_por_estatus->status_sol_id);
        $color_solicitud        = $tmp_array['color_solicitud'];
        $color_text_solicitud   = $tmp_array['color_text_solicitud'];
    @endphp
        <tr>
            <td class="small table-{{ $color_solicitud }} text-left text-{{ $color_text_solicitud }}">
                <strong>
                    {{ $solicitud_sin_lote_por_estatus->status_sol->name }}
                </strong>
            </td>
            <td class="small text-right">{{ $solicitud_sin_lote_por_estatus->total_solicitudes }}</td>
        </tr>
@empty
        <tr>
            <td>No hay solicitudes sin lote</td>
        </tr>
@endforelse
        </tbody>
    </table>
</div>


<div class="col-7">
    <table class="table table-sm table-striped">
        <thead class="small">
            <tr>
                <th class="text-left">Delegación</th>
                <th class="text-center">Movimiento</th>
                <th class="text-right"># solicitudes</th>
            </tr>
        </thead>
        <tbody class="text-monospace">
@forelse($solicitudes_sin_lote_por_del as $solicitud_sin_lote_por_del)
            <tr>
                <td class="small">
    @if( $del <> $solicitud_sin_lote_por_del->delegacion->id )
                    <strong>
                        {{ str_pad($solicitud_sin_lote_por_del->delegacion->id, 2, '0', STR_PAD_LEFT) }}
                        -
                        {{$solicitud_sin_lote_por_del->delegacion->name}}
                    </strong>
        @php
            $del = $solicitud_sin_lote_por_del->delegacion->id
        @endphp
    @endif
                </td>

                <td class="small text-center">
                    {{ $solicitud_sin_lote_por_del->movimiento->name }}
                </td>

                <td class="small text-right">
                    {{ $solicitud_sin_lote_por_del->total_solicitudes }}
                </td>
            </tr>
@empty
            <tr>
                <td>No hay solicitudes sin lote</td>
            </tr>
@endforelse
        </tbody>
    </table>
</div>


<div class="col-12">
    <table class="table table-sm table-striped">
        <thead class="small">
            <tr>
                <th># Lote (lote_id)</th>
                <th>Oficio CA</th>
                <th>Fecha oficio</th>
                <th># Ticket MSI</th>
                <th>Fecha de atención</th>
                <th class="text-right"># solicitudes</th>
                <th>Comentario</th>
            </tr>
        </thead>
        <tbody class="text-monospace">
@forelse($listado_lotes as $lote)
            <tr>
                <td class="small">
                    <a target="_blank" alt="Ver detalle del Lote" href="/ctas/admin/generatabla/{{$lote->id}}">
                        {{ $lote->num_lote}}({{$lote->id}})
                    </a>
                </td>
                <td class="small text-center">{{ $lote->num_oficio_ca }}</td>

                <td class="small">
                    {{ Helpers::formatdate2($lote->fecha_oficio_lote) }}
                </td>

                <td class="small">{{ $lote->ticket_msi }}</td>

                <td class="small">
                    {{ Helpers::format_datetime_compact($lote->attended_at) }}
                </td>

                <td class="small text-right">{{ $lote->total_solicitudes }}</td>
                <td class="small">{{ $lote->comment }}</td>
            </tr>
@empty
            <tr>
                <td>
                    No hay lotes registrados</p>
                </td>

            </tr>
@endforelse
        </tbody>
    </table>
</div>
