<div>
    <div>
        <h5 class="text-primary">Listado de cuentas activas - Afiliación</h5>
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
            @can('export_lista_ctas_vigentes_del')
                @if(count($active_accounts_list))
                    <div>
                        <a href="lista_ctas_vigentes_del/download" target="_blank" class="btn btn-danger">Exportar lista a archivo</a>
                    </div>
                @endif
            @endcan
        </p>
    </div>
    @if(count($active_accounts_list))
        <div>
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Cuenta</th>
                        <th scope="col">Origen</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Grupo</th>
                        <th scope="col">Matricula</th>
                        <th scope="col">Tipo Cta</th>
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
            <td class="small">
            @if($row_active_accounts->Id == "")
                {{ $row_active_accounts->Cuenta }}
            @else
                <a target="_blank" alt="Ver detalle cta"
                    href="/ctas/solicitudes/search/cta?search_word={{ substr($row_active_accounts->Cuenta, 0, 6) }}">
                        {{ $row_active_accounts->Cuenta }}
                </a>
            @endif
            </td>
            <td class="small">{{ $row_active_accounts->Mov }}</td>
            <td class="small">{{ $row_active_accounts->Nombre }}</td>
            <td class="small">{{ $row_active_accounts->Gpo_unificado }}</td>
            <td class="small">{{ $row_active_accounts->Matricula }}</td>
            <td class="small">{{ $row_active_accounts->Work_area_id == 2 ? 'Cta. Genérica' : "" }}</td>
        </tr>
    @empty
        <p>No hay cuentas registradas en esta delegación</p>
        <br>
        <hr>
    @endforelse

    @if(count($active_accounts_list))
                </tbody>
            </table>
        </div>
    @endif

</div>
