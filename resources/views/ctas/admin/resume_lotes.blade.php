@php
    $var = 0;
@endphp

<br>
<br>
@if(count($lista_ultimos_lotes))
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
                    <th scope="col">Fecha de Creación</th>
                    <th scope="col">Fecha de Actualización</th>
                </tr>
            </thead>
@endif

@forelse($lista_ultimos_lotes as $lote)
    <tbody>
    <tr>
        <td class="small">{{ $lote->num_lote}}</td>
        <td class="small">{{ $lote->num_oficio_ca }}</td>
        <td class="small">{{ $lote->fecha_oficio_lote }}</td>
        <td class="small">{{ $lote->ticket_msi }}</td>
        <td class="small">{{ $lote->attended_at }}</td>
        <td class="small">{{ $lote->total_solicitudes }}</td>
        <td class="small">{{ $lote->comment }}</td>
        <td class="small">{{ $lote->created_at }}</td>
        <td class="small">{{ $lote->updated_at }}</td>
    </tr>
    </tbody>
@empty
    <p>No hay lotes registrados</p>
@endforelse

@if(count($lista_ultimos_lotes))
        </table>
    </div>
@endif
