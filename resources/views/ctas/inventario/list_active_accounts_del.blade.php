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

    @if( $delegacion_a_consultar->id <> 0 )
        @if(count($active_accounts_list))
            <div>
                <table class="table table-sm table-striped">
                    <thead class="small">
                        <tr>
                            <th>#</th>
                            <th>Cuenta</th>
                            <th>¿Jubilado?</th>
                            <th>Nombre</th>
                            <th>Grupo</th>
                            <th>Matrícula</th>
                            <th>CURP</th>
                            <th>Subdelegación</th>
                        </tr>
                    </thead>
                    <tbody>
        @endif

        @php
            $var = 0;
            $color_solicitud = '';
        @endphp

        @forelse( $active_accounts_list as $row_active_accounts )
            @php
                $color_solicitud = '';
                if ( str_contains($row_active_accounts->Datos_siap1, 'JUBILA') ||
                    str_contains($row_active_accounts->Datos_siap2, 'JUBILA') )
                    $color_solicitud = 'text-danger';
                $var += 1;
            @endphp

            <tr class="small text-monospace {{ $color_solicitud }}">
                <th>{{ $var }}</th>

                {{-- Cuenta y Origen--}}
                <td>
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
                <td>
                    @if (str_contains($row_active_accounts->Datos_siap1, 'JUBILA') || str_contains($row_active_accounts->Datos_siap2, 'JUBILA'))
                        JUBILADO
                    @endif
                </td>

                {{-- Nombre --}}
                <td>
                    {{ $row_active_accounts->Nombre == '--' ? $row_active_accounts->Nombre_origen : $row_active_accounts->Nombre }}
                </td>

                {{-- Grupo --}}
                <td>
                    {{ $row_active_accounts->Gpo_unificado }}
                </td>

                {{-- Matricula --}}
                <td>
                    {{ $row_active_accounts->Matricula == "--" ? $row_active_accounts->Matricula_origen : $row_active_accounts->Matricula }}
                </td>

                {{-- CURP --}}
                <td>
                    {{ $row_active_accounts->CURP == '--' ?
                        ( $row_active_accounts->CURP_origen == '--' ? '' : $row_active_accounts->CURP_origen ) 
                            : $row_active_accounts->CURP}}
                </td>

                {{-- Subdel --}}
                <td>
                @if ($row_active_accounts->Subdel_numsub != 0)
                    {{ $row_active_accounts->Subdel_name == '' ? '' :
                    str_pad($row_active_accounts->Subdel_numsub, 2, '0', STR_PAD_LEFT) . '-' . $row_active_accounts->Subdel_name }}
                @endif
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
