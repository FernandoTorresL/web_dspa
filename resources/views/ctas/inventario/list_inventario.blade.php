<div class="container">

    <h5 class="text-primary">Listado (al corte)</h5>
    <br>

    @if(count($list_inventario))
        @can('ver_buscar_cta_inventario')
            <form action="/ctas/inventario">
                {{ csrf_field() }}
                <div class="col-sm-4">
                    <div class="input-group">
                        <input onClick="this.setSelectionRange(0, this.value.length)"
                               type="text" id="search_word" name="search_word"
                               class="form-control @if($errors->has('search_word')) is-invalid @endif"
                               value="{{ strtoupper($search_word) }}">
                        @if ($errors->has('search_word'))
                            @foreach($errors->get('search_word') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                        <span class="input-group-append">
                            <button class="btn btn-outline-success">Buscar</button>
                        </span>
                    </div>
                </div>
            </form>
            <br>
        @endcan

        <div class="row" align="center">
            @if( isset($search_word) )
                <h5 class="text-success">Cuentas localizadas con '{{ strtoupper($search_word) }}': {{ $list_inventario->total() }} </h5>
            @endif
            <div class="mt-2 mx-auto justify-content-center">
            {!! $list_inventario->appends(\Request::except('page'))->render() !!}
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
    @endif

    @php
        $var = 0;
    @endphp

    @forelse( $list_inventario as $row_inventario )

        @php
            $var += 1;
        @endphp
                    <tr class="text-monospace">
                        <td class="small"><strong>{{ ($list_inventario->currentPage() * $list_inventario->perPage()) + $var - $list_inventario->perPage() }}</strong></td>
                        <td class="small">{{ $row_inventario->cuenta }}</td>
                        <td class="small">{{ $row_inventario->ciz_id }}</td>
                        <td class="small">{{ $row_inventario->name }}</td>
                        <td class="small">{{ $row_inventario->gpo_owner->name }}</td>
                        <td class="small">{{ $row_inventario->install_data }}</td>
                        <td class="small">{{ $row_inventario->work_area->name }}</td>
                    </tr>
    @empty
            @if( isset($search_word) )
                <h5 class="text-primary">No se localizan cuentas en el inventario con '{{ strtoupper($search_word) }}'</h5>
            @else
                <p>No hay cuentas registradas en esta delegación</p>
            @endif
            <br>
            <hr>
    @endforelse

    @if(count($list_inventario))
                </tbody>
            </table>
        </div>

        <div class="row" align="center">
            <div class="mt-2 mx-auto justify-content-center">
            {!! $list_inventario->appends(\Request::except('page'))->render() !!}
            </div>
        </div>
    @endif

</div>
