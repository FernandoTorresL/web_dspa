@php
    use App\Http\Helpers\Helpers;

    $var = 0;
    //dd($solicitudes_sin_lote_por_del);
@endphp

@if(count($solicitudes_sin_lote_por_del))
    <div class="col-7">

        <table class="table table-sm table-striped">
            <thead class="small">
                <tr>
                    <th class="text-left">Delegación</th>
                    <th class="text-center">Movimiento</th>
                    <th class="text-center"># solicitudes</th>
                </tr>
            </thead>
            <tbody class="text-monospace">
@endif

@forelse($solicitudes_sin_lote_por_del as $solicitud_sin_lote_por_del)
    @php
        $var += 1;
        //dd($solicitud_sin_lote_por_del);
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

        <td class="small text-center">
            {{ $solicitud_sin_lote_por_del->total_solicitudes }}
        </td>
    </tr>
@empty
    <p>No hay solicitudes sin lote</p>
    <br>
    <hr>
@endforelse

@if(count($solicitudes_sin_lote_por_del))
        </tbody>
    </table>
</div>
@endif

<br>
@if(count($listado_lotes))
    <div class="col-12">

        <table class="table table-sm table-striped">
            <thead class="small">
                <tr>
                    <th># Lote (lote_id)</th>
                    <th>Oficio CA</th>
                    <th>Fecha oficio</th>
                    <th># Ticket MSI</th>
                    <th>Fecha de atención</th>
                    <th class="text-center">Cantidad de solicitudes</th>
                    <th>Comentario</th>
                </tr>
            </thead>
            <tbody class="text-monospace">
@endif

@forelse($listado_lotes as $lote)
    @php
        //dd($lote);
    @endphp
    <tr>

        <td class="small">
            <a target="_blank" alt="Ver detalle del Lote" href="/ctas/admin/generatabla/{{$lote->id}}">
                {{ $lote->num_lote}}({{$lote->id}})
            </a>
        </td>
        <td class="small">{{ $lote->num_oficio_ca }}</td>

        <td class="col-1 small">
            {{ Helpers::formatdate($lote->fecha_oficio_lote) }}
        </td>

        <td class="small">{{ $lote->ticket_msi }}</td>

        <td class="col-1 small">
            {{ Helpers::format_datetime_compact($lote->attended_at) }}
        </td>

        <td class="small text-center">{{ $lote->total_solicitudes }}</td>
        <td class="small">{{ $lote->comment }}</td>
    </tr>
@empty
    <p>No hay lotes registrados</p>
    <br>
@endforelse

@if(count($listado_lotes))
        </tbody>
    </table>
</div>
@endif
