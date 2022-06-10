@if(count($solicitudes_sin_lote))
    <div class="table table-sm">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Origen</th>
                {{--<th scope="col">Delegación</th>--}}
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
                <td class="small">{{ isset($solicitud_sin_lote->origen_id) ? $solicitud_sin_lote->origen_id : 'Sin valija'}}</td>
{{--                <td class="small">{{ $solicitud_sin_lote->delegacion_id }}</td>--}}
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
                    <th scope="col"># Lote (ID)</th>
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
        <td class="small">
            <a target="_blank" alt="Ver detalle del Lote" href="/ctas/admin/generatabla/{{$lote->id}}">
                {{ $lote->num_lote}} - {{$lote->id}}
            </a>
        </td>
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

