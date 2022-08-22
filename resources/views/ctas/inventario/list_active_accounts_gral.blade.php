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
        <p>Tome en consideración lo siguiente:
        </p>
        <p>
            <ul>
                <li>La información que se muestra es una extracción combinada y depurada del Inventario de DIDT-Mainframe, más cambios posteriores registrados como solicitudes autorizadas en éste Portal (ALTAS, BAJAS, CAMBIOS), por tanto…</li>

                <li>… no es oficial y no fue proporcionada en esta forma por DIDT-Mainframe; no puede utilizarse por ustedes como información formal, definitiva o para determinar alguna responsabilidad. Se adjunta sólo como referencia y apoyo solicitado.</li>

                <li>El nombre, apellidos, matrícula y el orden en que se muestran, se basa en información capturada por ustedes en las solicitudes o que así se muestra de origen en el Inventario de DIDT-Mainframe.
                </li>

                <li>Sólo se muestran cuentas de grupos de Afiliación, pero incluye los grupos de SSCAMC, SSCAMP, etc., sin embargo, no debería encontrar aquí las cuentas de Fiscalización (SSCFIZ), Clasificación de Empresas (SSCLAS), Pensiones (DDSUBD), Cobranza (SSAREE, EE*), etc.</li>

                <li>En la sección de Inventario, podría encontrar aún cuentas de otras áreas, sin embargo esa fue información que así proporcionó Mainframe-DIDT en su corte y desconocemos los movimientos de esas cuentas o si aún existen en SINDO ya que se gestionan en otras Coordinaciones</li>

                <li>Por todo lo anterior y ya que aún es una extracción de prueba/prototipo de esta División, pudiera tener  imprecisiones no detectadas; favor de enviar sus comentarios o informar si encuentra alguna irregularidad .
                </li>
            </ul>
            @can('export_lista_ctas_vigentes_gral')
                @if(count($active_accounts_list))
                    <div>
                        <a href="export/{{ $delegacion_a_consultar->id }}" target="_blank" class="btn btn-danger">Exportar lista de 
                            {{ $delegacion_a_consultar->id == 0 ? 'Nacional' : $delegacion_a_consultar->name  }}</a>
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
                <td class="small">{{ $row_active_accounts->Gpo_actual }}</td>

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
