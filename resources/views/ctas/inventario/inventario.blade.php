<div class="container">
    <div class="row" align="center">
        <div class="mt-2 mx-auto justify-content-center">
        {!! $list_inventario->appends(\Request::except('page'))->render() !!}
        </div>
    </div>
</div>

<div class="table table-hover table-sm">
    <table class="table">
        <thead class="thead-primary">
            <tr>
                <th scope="col">#</th>
                <th scope="col">@sortablelink('cuenta', 'Usuario')</th>
                <th scope="col">@sortablelink('ciz_id', 'CIZ')</th>
                <th scope="col">@sortablelink('name', 'Nombre Completo')</th>
                <th scope="col">@sortablelink('gpo_owner_id', 'Grupo')</th>
                <th scope="col">@sortablelink('install_data', 'Info')</th>
                <th scope="col">@sortablelink('work_area_id', 'Tipo Cuenta')</th>
            </tr>
        </thead>
    <tbody>

    @php
        $var = 0;
    @endphp

    @forelse( $list_inventario as $row_inventario )

        @php
            $var += 1;
        @endphp

        <tr>
            <td class="small"><strong>{{ ($list_inventario->currentPage() * $list_inventario->perPage()) + $var - $list_inventario->perPage() }}</strong></td>
            <td class="small">{{ $row_inventario->cuenta }}</td>
            <td class="small">{{ $row_inventario->ciz_id }}</td>
            <td class="small">{{ $row_inventario->name }}</td>
            <td class="small">{{ $row_inventario->gpo_owner->name }}</td>
            <td class="small">{{ $row_inventario->install_data }}</td>
            <td class="small">{{ $row_inventario->work_area->name }}</td>
        </tr>
    @empty
        <p>No hay cuentas registradas en esta delegación</p>
    @endforelse

    </tbody>
    </table>
</div>

<div class="row" align="center">
    <div class="mt-2 mx-auto justify-content-center">
    {!! $list_inventario->appends(\Request::except('page'))->render() !!}
    </div>
</div>
