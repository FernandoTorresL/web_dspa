<div>
    <div>

        @include('ctas.inventario.mensaje_list_active_accounts')

        <p>
            @can('export_lista_ctas_vigentes_del')
                @if(count($active_accounts_list))
                    <div>
                        <a href="export/{{ $delegacion_a_consultar->id }}"
                            target="_blank" 
                            class="btn btn-danger">
                            Exportar listado a archivo .csv
                        </a>
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
                            <th scope="col">Cuenta</th>
                            <th scope="col">¿Jubilado?</th>
                            <th scope="col">Apellidos-Nombre</th>
                            <th scope="col">Grupo</th>
                            <th scope="col">Matrícula</th>
                            <th scope="col">CURP</th>
                            <th scope="col">Subdelegación</th>
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
                <td class="small">
                    {{ $var }}
                </td>

                {{-- Cuenta y Origen--}}
                <td class="small">
                    @if($row_active_accounts->Id == "--")
                        @if($row_active_accounts->Mov == 'Inventario')
                            {{-- Resultado en inventario --}}
                            <a target="_blank" alt="Ver detalle cta en inventario"
                                href="/ctas/inventario?search_word={{ $row_active_accounts->Cuenta }}">
                                {{ $row_active_accounts->Cuenta }}
                            </a>
                        @else
                            {{-- Resultado en solicitud --}}
                                <a target="_blank" alt="Ver solicitudes de la cta"
                                    href="/ctas/solicitudes/search/cta?search_word={{ substr($row_active_accounts->Cuenta, 0, 6) }}">
                                        {{ $row_active_accounts->Cuenta }}
                                </a>
                        @endif
                    @else
                        @if( ($row_active_accounts->Mov == 'Inventario') && ($row_active_accounts->Id == ""))
                            {{-- Múltiples registros en inventario --}}
                            <a target="_blank" alt="Ver solicitudes de la cta"
                                href="/ctas/solicitudes/search/cta?search_word={{ substr($row_active_accounts->Cuenta, 0, 6) }}">
                                {{ $row_active_accounts->Cuenta }}
                            </a>
                        @else
                            {{-- Resultado en solicitud e inventario --}}
                            <a target="_blank" alt="Ver solicitudes de la cta"
                            href="/ctas/solicitudes/search/cta?search_word={{ substr($row_active_accounts->Cuenta, 0, 6) }}">
                                {{ $row_active_accounts->Cuenta }}
                            </a>
                        @endif
                    @endif
                </td>

                {{-- Jubilado --}}
                <td class="small">
                    @if (str_contains($row_active_accounts->Datos_siap1, 'JUBILA') || str_contains($row_active_accounts->Datos_siap2, 'JUBILA'))
                        <p class="text-danger">
                            JUBILADO
                        </p>
                    @endif
                </td>

                {{-- Nombre --}}
                <td class="small">
                    {{ $row_active_accounts->Nombre == '--' ? $row_active_accounts->Nombre_origen : $row_active_accounts->Nombre }}
                </td>

                {{-- Grupo --}}
                <td class="small">
                    {{ $row_active_accounts->Gpo_unificado }}
                </td>

                {{-- Matricula --}}
                <td class="small">
                    {{ $row_active_accounts->Matricula == "--" ? $row_active_accounts->Matricula_origen : $row_active_accounts->Matricula }}
                </td>

                {{-- CURP --}}
                <td class="small">
                    {{ $row_active_accounts->CURP == '--' ?
                        ( $row_active_accounts->CURP_origen == '--' ? '' : $row_active_accounts->CURP_origen ) 
                            : $row_active_accounts->CURP}}
                </td>

                {{-- Subdel --}}
                <td class="small">
                    {{ $row_active_accounts->Subdel_name == '' ? '' : $row_active_accounts->Subdel_name }}
                </td>

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
<br>
