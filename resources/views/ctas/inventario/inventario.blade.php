@if(count($listado_detalle_ctas))
    {{--Muestra encabezado de tabla--}}
    <div class="table table-sm">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Usuario</th>
                <th scope="col">Nombre Completo</th>
                <th scope="col">Grupo</th>
                <th scope="col">Info</th>
                <th scope="col">Tipo Cuenta</th>
            </tr>
            </thead>
@endif

@php
    $var = 0;
@endphp

@forelse($listado_detalle_ctas as $detalle_cta)
    @php
        $var += 1;
    @endphp
            <tbody>
                <tr>
                    <th scope="row">{{ $var }}</th>
                    <td class="small">{{ $detalle_cta->cuenta }}</td>
                    <td class="small">{{ $detalle_cta->name }}</td>
                    <td class="small">{{ $detalle_cta->gpo_owner->name }}</td>
                    <td class="small">{{ $detalle_cta->install_data }}</td>
                    <td class="small">{{ $detalle_cta->work_area->name }}</td>
                </tr>
            </tbody>
@empty
    <p>No hay cuentas registradas en esta Delegación</p>
@endforelse

@if(count($listado_detalle_ctas))
        </table>
    </div>
@endif