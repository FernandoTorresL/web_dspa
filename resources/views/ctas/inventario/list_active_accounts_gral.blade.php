<div>
    <div>
        <h5 class="text-primary">
            @if ($delegacion_a_consultar->id == 0)
                Listado de cuentas vigentes Afiliación ADMIN - Nacional
            @else
                Cuentas vigentes Afiliación ADMIN - OOAD {{ $delegacion_a_consultar->name }}
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

    {{-- @if( $delegacion_a_consultar->id <> 0 ) --}}
        @if(count($active_accounts_list))
            <div>
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col"></th>
                            <th scope="col">Cuenta</th>
                            <th scope="col">Origen</th>
                            <th scope="col"><p>Apellidos-Nombre</p>Nombre Inventario</th>
                            <th scope="col">Grupo</th>
                            <th scope="col">Matricula</th>
                            <th scope="col">CURP</th>
                            <th scope="col">Tipo_Cta</th>
                            <th scope="col">Puesto</th>
                            <th scope="col">Fecha_mov</th>
                        </tr>
                    </thead>
                    <tbody>
        @endif

        @php
            $var = 0;
            //dd($active_accounts_list);
        @endphp

        @forelse( $active_accounts_list as $row_active_accounts )
            @php
                $var += 1;
            @endphp
            <tr class="text-monospace">
                <th scope="row">{{ $var }}</th>

                {{-- Estatus --}}
                <td class="small">
                    @if (str_contains($row_active_accounts->Datos_siap1, 'JUBILA') || str_contains($row_active_accounts->Datos_siap2, 'JUBILA'))
                        <p class="text-danger">
                            JUBILADO
                        </p>
                    @endif
                </td>

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

                <td class="small">
                @if( isset($row_active_accounts->Datos_siap) )
                    @php
                        $array = (array) $row_active_accounts->Datos_siap;
                    @endphp
                    @forelse( $array as $row_datos_siap )
                        @php
                            $color = "success"
                        @endphp

                        @if( isset($row_datos_siap[0]) )
                                # de Puestos:{{ count($row_datos_siap)}}
                            @if (str_contains($row_datos_siap[0]['adscripcion'], 'JUB') )
                                @php
                                    $color = "danger"
                                @endphp
                            @endif
                                <p class="text-{{$color}}">
                                    Del:{{ $row_datos_siap[0]['delegacion_id'] }}|{{ $row_datos_siap[0]['primer_apellido'] }}-{{ $row_datos_siap[0]['segundo_apellido'] }}-{{ $row_datos_siap[0]['nombre'] }}|
                                    {{ $row_datos_siap[0]['cve_adscripcion'] }}|{{ $row_datos_siap[0]['adscripcion'] }}|
                                    {{ $row_datos_siap[0]['cve_puesto'] }}|{{ $row_datos_siap[0]['puesto'] }}|
                                </p>
                                @if( isset($row_datos_siap[1]) )
                                    @if (str_contains($row_datos_siap[1]['adscripcion'], 'JUB') )
                                        @php
                                            $color = "danger"
                                        @endphp
                                    @endif
                                    <p class="text-{{$color}}">
                                        Del:{{ $row_datos_siap[1]['delegacion_id'] }}|{{ $row_datos_siap[1]['primer_apellido'] }}-{{ $row_datos_siap[1]['segundo_apellido'] }}-{{ $row_datos_siap[1]['nombre'] }}|
                                        {{ $row_datos_siap[1]['cve_adscripcion'] }}|{{ $row_datos_siap[1]['adscripcion'] }}|
                                        {{ $row_datos_siap[1]['cve_puesto'] }}|{{ $row_datos_siap[1]['puesto'] }}|
                                    </p>
                                @endif
                                @if( isset($row_datos_siap[2]) )
                                    @if (str_contains($row_datos_siap[2]['adscripcion'], 'JUB') )
                                        @php
                                            $color = "danger"
                                        @endphp
                                    @endif
                                    <p class="text-{{$color}}">
                                        Del:{{ $row_datos_siap[2]['delegacion_id'] }}|{{ $row_datos_siap[2]['primer_apellido'] }}-{{ $row_datos_siap[2]['segundo_apellido'] }}-{{ $row_datos_siap[2]['nombre'] }}|
                                        {{ $row_datos_siap[2]['cve_adscripcion'] }}|{{ $row_datos_siap[2]['adscripcion'] }}|
                                        {{ $row_datos_siap[2]['cve_puesto'] }}|{{ $row_datos_siap[2]['puesto'] }}|
                                    </p>
                                @endif
                                @if( isset($row_datos_siap[3]) )
                                    @if (str_contains($row_datos_siap[3]['adscripcion'], 'JUB') )
                                        @php
                                            $color = "danger"
                                        @endphp
                                    @endif
                                    <p class="text-{{$color}}">
                                        Del:{{ $row_datos_siap[3]['delegacion_id'] }}|{{ $row_datos_siap[3]['primer_apellido'] }}-{{ $row_datos_siap[3]['segundo_apellido'] }}-{{ $row_datos_siap[3]['nombre'] }}|
                                        {{ $row_datos_siap[3]['cve_adscripcion'] }}|{{ $row_datos_siap[3]['adscripcion'] }}|
                                        {{ $row_datos_siap[3]['cve_puesto'] }}|{{ $row_datos_siap[3]['puesto'] }}|
                                    </p>
                                @endif
                        @else
                        ---
                        @endif

                    @empty

                    @endforelse
                @else
                    --
                @endif
                </td>

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
    {{-- @endif --}}
</div>
