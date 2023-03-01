@php
    use App\Http\Helpers\Helpers;

    $var = 0;
@endphp

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
    @php
        $var += 1;
    @endphp
            <tr>
                <td class="small text-center">{{ $solicitud_sin_lote_por_mov->movimiento->name }}</td>
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

@php
    $var = 0;
@endphp

<div class="col-4">
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
        $var += 1;
    @endphp
        <tr>
            <td class="small text-left">{{ $solicitud_sin_lote_por_estatus->status_sol->name }}</td>
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
    @php
        $var += 1;
    @endphp
            <tr>
                <td class="small">
                    {{ str_pad($solicitud_sin_lote_por_del->delegacion->id, 2, '0', STR_PAD_LEFT) }}
                    -
                    {{$solicitud_sin_lote_por_del->delegacion->name}}
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
