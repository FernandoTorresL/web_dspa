@if( count($list_inventario) )
    {{--Muestra encabezado de tabla--}}
    <div class="table">
        <table class="table">
            <tr class="small">
                <th scope="col">#</th>
                <th scope="col">@sortablelink('cuenta', 'Usuario')</th>
                <th scope="col">@sortablelink('ciz_id', 'CIZ')</th>
                <th scope="col">@sortablelink('name', 'Nombre Completo')</th>
                <th scope="col">@sortablelink('gpo_owner_id', 'Grupo')</th>
                <th scope="col">@sortablelink('install_data', 'Info')</th>
                <th scope="col">@sortablelink('work_area_id', 'Tipo Cuenta')</th>
            </tr>
@endif

@php
    $var = 0;
@endphp

@forelse( $list_inventario as $row_inventario )

    @php
        $var += 1;
    @endphp
    <tr>
        <td class="small">{{ ($list_inventario->currentPage() * $list_inventario->perPage()) + $var - $list_inventario->perPage() }}</td>
        <td class="small">{{ $row_inventario->cuenta }}</td>
        <td class="small">{{ $row_inventario->CIZ1==1 ? 1 : '-'}}|{{ $row_inventario->CIZ2==1 ? 2 : '-' }}|{{ $row_inventario->CIZ3==1 ? 3 : '-' }}</td>
        <td class="small">{{ $row_inventario->name }}</td>
        <td class="small">{{ $row_inventario->gpo_name }}</td>
        <td class="small">{{ $row_inventario->install_data }}</td>
        <td class="small">{{ $row_inventario->work_area_name }}</td>
    </tr>
@empty
    <p>No hay cuentas registradas en esta delegación</p>
@endforelse

@if( count($list_inventario) )
        </table>
    </div>

    <div class="mt-2 mx-auto justify-content-center">
        {!! $list_inventario->appends(\Request::except('page'))->render() !!}
    </div>
@endif
