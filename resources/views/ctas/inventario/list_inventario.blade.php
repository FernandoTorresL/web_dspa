<div class="container">

    <h5 class="text-primary">Listado (Inventario al corte)</h5>
    <br>

    
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

    @if(count($new_inventory_list))
        <div class="row" align="center">
            @if( isset($search_word) )
                <h5 class="text-success">Cuentas localizadas con '{{ strtoupper($search_word) }}': {{ $new_inventory_list->total() }} </h5>
            @endif
            <div class="mt-2 mx-auto justify-content-center">
            {!! $new_inventory_list->appends(\Request::except('page'))->render() !!}
            </div>
        </div>

        <div>
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@sortablelink('cuenta', 'Usuario')</th>
                        <th scope="col">@sortablelink('cizs', 'CIZs')</th>
                        <th scope="col">@sortablelink('name', 'Nombre completo')</th>
                        <th scope="col">@sortablelink('grupo.name', 'Grupo')</th>
                        <th scope="col">@sortablelink('install_data', 'Información')</th>
                        <th scope="col">@sortablelink('tipo_cuenta.name', 'Área / Tipo Cuenta')</th>
                        <th scope="col">Observaciones</th>
                    </tr>
                </thead>
                <tbody>
    @endif

    @php
        $var = 0;
    @endphp

    @forelse( $new_inventory_list as $row_inventario )

        @php
            $var += 1;
        @endphp

        {{-- Setting row color in red for 'cuentas' deleted after inventory cutoffdate --}}
        @if( $row_inventario->registros_en_baja->isNotEmpty() )
            @if ($row_inventario->registros_en_baja[0]->solicitud->movimiento_id == 3)
                <tr class="table-warning text-monospace">
            @else
                <tr class="table-danger text-monospace">
            @endif
        @else
                <tr class="text-monospace">
        @endif
                    <th scope="row">{{ ($new_inventory_list->currentPage() * $new_inventory_list->perPage()) + $var - $new_inventory_list->perPage() }}</th>
                    <td class="small">{{ $row_inventario->cuenta }}</td>
                    <td class="small">{{ $row_inventario->ciz_1 ? 1 : '-' }}|{{ $row_inventario->ciz_2 ? 2 : '-' }}|{{ $row_inventario->ciz_3 ?  3 : '-' }}</td>
                    <td class="small">{{ $row_inventario->name }}</td>
                    <td class="small">{{ $row_inventario->gpo_owner->name }}</td>
                    <td class="small text-wrap" style="width: 8rem;">{{ $row_inventario->install_data }}</td>
                    <td class="small text-wrap" style="width: 16rem;">{{ $row_inventario->work_area->name }}</td>
                    <td class="small text-wrap" style="width: 16rem;">
                    @if( $row_inventario->registros_en_baja->isNotEmpty() )
                        @forelse( $row_inventario->registros_en_baja as $registro_en_baja )
                            <a target="_blank" alt="Ver/Editar" href="/ctas/solicitudes/{{ $registro_en_baja->solicitud_id }}">
                            @if ($registro_en_baja->solicitud->movimiento_id == 3)
                                {{ 'CAMBIO|Grupo nuevo: ' . $registro_en_baja->solicitud->gpo_nuevo->name }}
                            @else
                                {{ $registro_en_baja->name ? 
                                    'BAJA|Nombre que reportó Mainframe: ' . $registro_en_baja->name : 'BAJA' }}
                            @endif
                            </a>
                        @empty

                        @endforelse
                    @endif
                    </td>
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

    @if(count($new_inventory_list))
                </tbody>
            </table>
        </div>

        <div class="row" align="center">
            <div class="mt-2 mx-auto justify-content-center">
            {!! $new_inventory_list->appends(\Request::except('page'))->render() !!}
            </div>
        </div>
    @endif

</div>
