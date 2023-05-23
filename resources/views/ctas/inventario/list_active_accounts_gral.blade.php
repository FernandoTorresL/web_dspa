@php
    use App\Http\Helpers\Helpers;

    $var = 0;
@endphp

<div>
        @include('ctas.inventario.mensaje_list_active_accounts')

        <p>
            @can('export_lista_ctas_vigentes_gral')
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

        @if(count($active_accounts_list))
            <div>
                <table class="table table-sm table-striped">
                    <thead class="small">
                        <tr>
                            <th>#</th>
                            <th>Cuenta</th>
                            <th>Origen</th>
                            <th>¿Jubilado?</th>
                            <th>Nombre</th>
                            <th>Grupo</th>
                            <th>Matrícula</th>
                            <th>CURP</th>
                            <th>Puesto</th>
                            <th>Tipo_Cta</th>
                            <th>Fecha_mov</th>
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
            @if($row_active_accounts->Id == "--")
                @if($row_active_accounts->Mov == 'Inventario')
                {{-- Resultado en inventario --}}
                {{-- Cuenta --}}
                    <td>
                        <a target="_blank" alt="Ver detalle cta en inventario"
                            href="/ctas/inventario?search_word={{ $row_active_accounts->Cuenta }}">
                            {{ $row_active_accounts->Cuenta }}
                        </a>
                    </td>
                            {{-- Origen --}}
                    <td>
                        <a target="_blank" alt="Ver detalle cta en inventario"
                            href="/ctas/inventario?search_word={{ $row_active_accounts->Cuenta }}">
                            {{ $row_active_accounts->Mov }}
                        </a>
                    </td>
                @else
                        {{-- Resultado en solicitud --}}
                        {{-- Cuenta --}}
                    <td>
                        <a target="_blank" alt="Ver solicitudes de la cta"
                            href="/ctas/solicitudes/search/cta?search_word={{ substr($row_active_accounts->Cuenta, 0, 6) }}">
                                {{ $row_active_accounts->Cuenta }}
                        </a>
                    </td>
                        {{-- Origen --}}
                    <td>
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
                    <td>
                        <a target="_blank" alt="Ver solicitudes de la cta"
                            href="/ctas/solicitudes/search/cta?search_word={{ substr($row_active_accounts->Cuenta, 0, 6) }}">
                            {{ $row_active_accounts->Cuenta }}
                        </a>
                    </td>
                    {{-- Origen --}}
                    <td>
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
                    <td>
                        <a target="_blank" alt="Ver solicitudes de la cta"
                        href="/ctas/solicitudes/search/cta?search_word={{ substr($row_active_accounts->Cuenta, 0, 6) }}">
                            {{ $row_active_accounts->Cuenta }}
                        </a>
                    </td>

                    {{-- Origen --}}
                    <td>
                        <p>
                            <a target="_blank" alt="Ver detalle solicitud"
                            href="/ctas/solicitudes/{{ $row_active_accounts->Id == "--" ? $row_active_accounts->Id_origen : $row_active_accounts->Id }}">
                                Solicitud
                            </a>
                            e
                            <a target="_blank" alt="Ver detalle solicitud"
                            href="/ctas/inventario?search_word={{ $row_active_accounts->Cuenta }}">
                                {{ $row_active_accounts->Mov }}
                            </a>
                        </p>
                    </td>
                @endif
            @endif

            {{-- Estatus --}}
                <td>
                    @if (str_contains($row_active_accounts->Datos_siap1, 'JUBILA') || str_contains($row_active_accounts->Datos_siap2, 'JUBILA'))
                        <p class="text-danger">
                            JUBILADO
                        </p>
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
                    {{ $row_active_accounts->CURP == "--" ? 
                        $row_active_accounts->CURP_origen == "--" ? '' : $row_active_accounts->CURP_origen
                        : '' }}
                </td>

                <td>
                @if( isset($row_active_accounts->Datos_siap) )
                    @php
                        $array = (array) $row_active_accounts->Datos_siap;
                    @endphp
                    @forelse( $array as $row_datos_siap )
                        @php
                            $color = "success"
                        @endphp

                        @if( isset($row_datos_siap[0]) )

                            @if (str_contains($row_datos_siap[0]['adscripcion'], 'JUB') )
                                @php
                                    $color = "danger"
                                @endphp
                            @endif
                            <p class="small text-{{$color}}">
                                #{{ count($row_datos_siap)}}|{{$row_datos_siap[0]['primer_apellido'] }}-{{ $row_datos_siap[0]['segundo_apellido'] }}-{{ $row_datos_siap[0]['nombre']}}|{{$row_datos_siap[0]['adscripcion']}}|{{$row_datos_siap[0]['puesto']}}
                            </p>

                            @if( isset($row_datos_siap[1]) )
                                @if (str_contains($row_datos_siap[1]['adscripcion'], 'JUB') )
                                    @php
                                        $color = "danger"
                                    @endphp
                                @endif
                                <p class="small text-{{$color}}">
                                    {{$row_datos_siap[1]['primer_apellido'] }}-{{ $row_datos_siap[1]['segundo_apellido'] }}-{{ $row_datos_siap[1]['nombre']}}|{{$row_datos_siap[1]['adscripcion']}}|{{$row_datos_siap[1]['puesto']}}
                                </p>
                            @endif

                            @if( isset($row_datos_siap[2]) )
                                @if (str_contains($row_datos_siap[2]['adscripcion'], 'JUB') )
                                    @php
                                        $color = "danger"
                                    @endphp
                                @endif
                                <p class="small text-{{$color}}">
                                    {{$row_datos_siap[2]['primer_apellido'] }}-{{ $row_datos_siap[2]['segundo_apellido'] }}-{{ $row_datos_siap[2]['nombre']}}|{{$row_datos_siap[2]['adscripcion']}}|{{$row_datos_siap[2]['puesto']}}
                                </p>
                            @endif

                            @if( isset($row_datos_siap[3]) )
                                @if (str_contains($row_datos_siap[3]['adscripcion'], 'JUB') )
                                    @php
                                        $color = "danger"
                                    @endphp
                                @endif
                                <p class="small text-{{$color}}">
                                    {{$row_datos_siap[3]['primer_apellido'] }}-{{ $row_datos_siap[3]['segundo_apellido'] }}-{{ $row_datos_siap[3]['nombre']}}|{{$row_datos_siap[3]['adscripcion']}}|{{$row_datos_siap[3]['puesto']}}
                                </p>
                            @endif

                        @endif

                    @empty

                    @endforelse

                @endif
                </td>

                                {{-- Tipo Cta --}}
                <td>
                    {{ $row_active_accounts->Work_area_id == 2 ? 'Cta. Genérica': '' }}
                </td>

                {{-- Fecha mov --}}
                <td>
                    {{ Helpers::format_datetime_short($row_active_accounts->Fecha_mov) }}
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

</div>
<br>
