<div>
    <div>
        <h5 class="text-primary">
            @if ($delegacion_a_consultar->id == 0)
                Listado de cuentas activas Afiliación ADMIN - Nacional
            @else
                Cuentas activas Afiliación ADMIN - OOAD {{ $delegacion_a_consultar->name }}
                ({{ str_pad($delegacion_a_consultar->id , 2, '0', STR_PAD_LEFT) }})
            @endif
        </h5>

        @include('ctas.inventario.mensaje_list_active_accounts')

        <p>
            @can('export_lista_ctas_vigentes_gral')
                @if(count($active_accounts_list))
                    <div>
                        <a href="export/{{ $delegacion_a_consultar->id }}" target="_blank" class="btn btn-danger">Exportar lista 
                            {{ $delegacion_a_consultar->id == 0 ? 'Nacional' : 'de ' .$delegacion_a_consultar->name  }}</a>
                    </div>
                @endif
            @endcan
        </p>
    </div>

    @if( $delegacion_a_consultar->id <> 0 )
        @if(count($active_accounts_list))
            <div>
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">id</th>
                            <th scope="col">Del</th>
                            <th scope="col">Cuenta</th>
                            <th scope="col">Origen</th>
                            <th scope="col"><p>Apellidos-Nombre</p>Nombre Inventario</th>
                            <th scope="col">Grupo</th>
                            <th scope="col">Matricula</th>
                            <th scope="col">CURP</th>
                            <th scope="col">Tipo_Cta</th>
                            <th scope="col">Fecha_mov</th>
                        </tr>
                    </thead>
                    <tbody>
        @endif

        @php
            $var = 0;
        @endphp

        @forelse( $active_accounts_list as $row_active_accounts )
            @php
                $var += 1;
            @endphp
            <tr class="text-monospace">
                <th scope="row">{{ $var }}</th>

                {{-- Id Final --}}
                <td class="small">
                    <a target="_blank" alt="Ver detalle de la solicitud"
                        href="/ctas/solicitudes/{{ $row_active_accounts->Id == "--" ? $row_active_accounts->Id_origen : $row_active_accounts->Id }}">
                        {{ $row_active_accounts->Id == "--" ? $row_active_accounts->Id_origen : $row_active_accounts->Id }}
                    </a>
                </td>

                {{-- Delegación Id--}}
                <td class="small">{{ $row_active_accounts->Del_id }}</td>

                {{-- Cuenta y Origen--}}
                @if($row_active_accounts->Id == "--")
                    @if($row_active_accounts->Mov == 'Inventario')
                        {{-- Resultado en inventario --}}
                        {{-- Cuenta --}}
                        <td class="small">
                            <a target="_blank" alt="Ver detalle cta en inventario"
                                href="/ctas/inventario?search_word={{ $row_active_accounts->Cuenta }}">
                                {{ $row_active_accounts->Cuenta }}
                            </a>
                        </td>
                        {{-- Origen --}}
                        <td class="small">
                            <a target="_blank" alt="Ver detalle cta en inventario"
                                href="/ctas/inventario?search_word={{ $row_active_accounts->Cuenta }}">
                                {{ $row_active_accounts->Mov }}
                            </a>
                        </td>
                    @else
                        {{-- Resultado en solicitud --}}
                        {{-- Cuenta --}}
                        <td class="small">
                            <a target="_blank" alt="Ver solicitudes de la cta"
                                href="/ctas/solicitudes/search/cta?search_word={{ substr($row_active_accounts->Cuenta, 0, 6) }}">
                                    {{ $row_active_accounts->Cuenta }}
                            </a>
                        </td>
                        {{-- Origen --}}
                        <td class="small">
                            <a target="_blank" alt="Ver detalle solicitud"
                                href="/ctas/solicitudes/{{ $row_active_accounts->Id_origen }}">
                                {{ $row_active_accounts->Mov }}
                            </a>
                        </td>
                    @endif
                @else
                    @if( ($row_active_accounts->Mov == 'Inventario') && ($row_active_accounts->Id == ""))
                        {{-- Múltiples registros en inventario --}}
                        {{-- Cuenta --}}
                        <td class="small">
                            <a target="_blank" alt="Ver solicitudes de la cta"
                                href="/ctas/solicitudes/search/cta?search_word={{ substr($row_active_accounts->Cuenta, 0, 6) }}">
                                {{ $row_active_accounts->Cuenta }}
                            </a>
                        </td>
                        {{-- Origen --}}
                        <td class="small">
                            <a target="_blank" alt="Ver detalle solicitud"
                            href="/ctas/inventario?search_word={{ $row_active_accounts->Cuenta }}">
                                <p>
                                    Múltiples registros en
                                </p>{{ $row_active_accounts->Mov }}
                            </a>
                        </td>
                    @else
                        {{-- Resultado en solicitud e inventario --}}
                        {{-- Cuenta --}}
                        <td class="small">
                            <a target="_blank" alt="Ver solicitudes de la cta"
                            href="/ctas/solicitudes/search/cta?search_word={{ substr($row_active_accounts->Cuenta, 0, 6) }}">
                                {{ $row_active_accounts->Cuenta }}
                            </a>
                        </td>
                        {{-- Origen --}}
                        <td class="small">
                            <p>
                                <a target="_blank" alt="Ver detalle solicitud"
                                href="/ctas/solicitudes/{{ $row_active_accounts->Id == "--" ? $row_active_accounts->Id_origen : $row_active_accounts->Id }}">
                                    Solicitud
                                </a>
                            </p>
                            e
                            <a target="_blank" alt="Ver detalle solicitud"
                            href="/ctas/inventario?search_word={{ $row_active_accounts->Cuenta }}">
                                {{ $row_active_accounts->Mov }}
                            </a>
                        </td>
                    @endif
                @endif

                {{-- Nombre --}}
                <td class="small">
                    @if( $row_active_accounts->Nombre == "--" )
                        <p>{{ $row_active_accounts->Nombre_origen }}</p>
                    @else
                        <p>{{ $row_active_accounts->Nombre }}</p>
                        {{ $row_active_accounts->Nombre_origen }}
                    @endif
                </td>

                {{-- Grupo --}}
                <td class="small">{{ $row_active_accounts->Gpo_unificado }}</td>

                {{-- Matricula --}}
                <td class="small">{{ $row_active_accounts->Matricula == "--" ? $row_active_accounts->Matricula_origen : $row_active_accounts->Matricula }}</td>

                {{-- CURP --}}
                <td class="small">{{ $row_active_accounts->CURP == "--" ? $row_active_accounts->CURP_origen : $row_active_accounts->CURP }}</td>

                {{-- Tipo Cta --}}
                <td class="small">{{ $row_active_accounts->Work_area_id == 2 ? 'Cta. Genérica': '' }}</td>

                {{-- Fecha mov --}}
                <td class="small">{{ $row_active_accounts->Fecha_mov }}</td>
            </tr>
        @empty
            <p>¡No hay cuentas registradas!</p>
            <br>
            <hr>
        @endforelse

        @if(count($active_accounts_list))
                    </tbody>
                </table>
            </div>
        @endif
    @endif
</div>
