<div class="col-12 ">
    <h5>
        Ver listado de:
    </h5>
    @forelse($delegaciones as $delegacion)
        <a href="/ctas/lista_ctas_vigentes_gral/{{ $delegacion->id }}" target="_blank" class="btn btn-outline-primary btn-sm">
            {{ str_pad($delegacion->id, 2, '0', STR_PAD_LEFT) }} - {{ $delegacion->name }}
        </a>
    @empty
        <p>
            ¡No hay OOAD's registradas!
        </p>
    @endforelse
    <br>
    <br>

    <div class="card-group">
        @if ($delegacion_a_consultar->id <> 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-center">Subdelegaciones</h5>
                </div>

                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($subdelegaciones as $subdelegacion)
                            @if ($subdelegacion->num_sub <> 0)
                                <li class="list-group-item text-truncate">
                                    {{ str_pad($subdelegacion->num_sub, 2, '0', STR_PAD_LEFT) }} - {{ $subdelegacion->name }}
                                </li>
                            @endif
                        @empty
                            <li class="list-group-item">
                                No hay subdelegaciones registradas
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="card-title text-center">Conteo
                    {{ $delegacion_a_consultar->id == 0 ? 'Nacional' : $delegacion_a_consultar->name  }}
                </h5>
            </div>

            <div class="card-body">
                <ul class="list-group list-group-flush">

                    @if($total_ctas_Genericas == 0 )
                        @php
                            $color = 'success';
                            $mensaje = '';
                        @endphp
                    @else
                        @php
                            $color = 'danger';
                            $mensaje = 'Depurar';
                        @endphp
                    @endif
                    <li class="list-group-item">
                        <button type="button" class="btn btn-{{ $color }} btn-sm" data-toggle="tooltip" data-placement="top">
                            Genéricas <span class="badge badge-light"> {{ $total_ctas_Genericas }}</span>
                        </button>
                        <small class="text-muted text-color-dark">{{ $mensaje }}</small>
                    </li>

                    @if($delegacion_a_consultar->id == 0)
                        @php
                            $comparador = count($delegaciones) - 1;
                        @endphp
                    @else
                        @php
                            $comparador = 1;
                        @endphp
                    @endif


                    @if($total_ctas_SSJSAV == $comparador )
                        @php
                            $color = 'success';
                            $mensaje = '';
                        @endphp
                    @elseif($total_ctas_SSJSAV < $comparador )
                        @php
                            $guion = '-';
                            $color = 'warning';
                            $mensaje = '¿Faltan cuentas?';
                        @endphp
                    @else
                        @php
                            $color = 'danger';
                            $mensaje = '¡Como máximo deben existir ' . $comparador . ' cuentas!';
                        @endphp
                    @endif
                    <li class="list-group-item">
                        <button type="button" class="btn btn-{{ $color }} btn-sm" data-toggle="tooltip" data-placement="top">
                            SSJSAV <span class="badge badge-light">{{ $total_ctas_SSJSAV }}</span>
                        </button>
                        <small class="text-muted text-color-dark">{{ $mensaje }}</small>
                    </li>

                    @if($total_ctas_SSJDAV == count($subdelegaciones) - 1 )
                        @php
                            $color = 'success';
                            $mensaje = '';
                        @endphp
                    @elseif($total_ctas_SSJDAV < count($subdelegaciones) - 1 )
                        @php
                            $guion = '-';
                            $color = 'warning';
                            $mensaje = '¿Faltan cuentas?';
                        @endphp
                    @else
                        @php
                            $color = 'danger';
                            $mensaje = '¿Demasiadas cuentas?';
                        @endphp
                    @endif
                    <li class="list-group-item">
                        <button type="button" class="btn btn-{{ $color }} btn-sm" data-toggle="tooltip" data-placement="top">
                            SSJDAV <span class="badge badge-light">{{ $total_ctas_SSJDAV }}</span>
                        </button>
                        <small class="text-muted text-color-dark">{{ $mensaje }}</small>
                    </li>

                    @if($total_ctas_SSJOFA == count($subdelegaciones) - 1 )
                        @php
                            $guion = '-';
                            $color = 'success';
                            $mensaje = '';
                        @endphp
                    @elseif($total_ctas_SSJOFA <= count($subdelegaciones) - 1 )
                        @php
                            $guion = '-';
                            $color = 'warning';
                            $mensaje = '¿Faltan cuentas?';
                        @endphp
                    @else
                        @php
                            $guion = '-';
                            $color = 'danger';
                            $mensaje = '¿Demasiadas cuentas?';
                        @endphp
                    @endif
                    <li class="list-group-item">
                        <button type="button" class="btn btn-{{ $color }} btn-sm" data-toggle="tooltip" data-placement="top">
                            SSJOFA <span class="badge badge-light">{{ $total_ctas_SSJOFA }}</span>
                        </button>
                        <small class="text-muted text-color-dark">{{ $mensaje }}</small>
                    </li>

                    <li class="list-group-item">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top">
                            SSJVIG <span class="badge badge-light">{{ $total_ctas_SSJVIG }}</span>
                        </button>
                    </li>

                    <li class="list-group-item">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top">
                            SSCONS <span class="badge badge-light">{{ $total_ctas_SSCONS }}</span>
                        </button>
                    </li>

                    <li class="list-group-item">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top">
                            SSOPER <span class="badge badge-light"> {{ $total_ctas_SSOPER }}</span>
                        </button>
                    </li>

                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title text-center">
                    Conteo
                    {{ $delegacion_a_consultar->id == 0 ? 'Nacional' : $delegacion_a_consultar->name  }}
                </h5>
            </div>

            <div class="card-body">
                <ul class="list-group list-group-flush">

                    <li class="list-group-item">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top">
                            SSCERT <span class="badge badge-light"> {{ $total_ctas_SSCERT }}</span>
                        </button>
                    </li>

                    <li class="list-group-item">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top">
                            SSCAMC <span class="badge badge-light"> {{ $total_ctas_SSCAMC }}</span>
                        </button>
                    </li>

                    <li class="list-group-item">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top">
                            SSCAUM <span class="badge badge-light"> {{ $total_ctas_SSCAUM }}</span>
                        </button>
                    </li>

                    <li class="list-group-item">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top">
                            SSCAPC <span class="badge badge-light"> {{ $total_ctas_SSCAPC }}</span>
                        </button>
                    </li>

                    <li class="list-group-item">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top">
                            SSCAMP <span class="badge badge-light"> {{ $total_ctas_SSCAMP }}</span>
                        </button>
                    </li>

                    <li class="list-group-item">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top">
                            SSADIF <span class="badge badge-light">{{ $total_ctas_SSADIF }}</span>
                        </button>
                    </li>

                    @if($total_ctas_SVC == ( count($delegaciones) - 1 ) )
                        @php
                            $color = 'success';
                            $mensaje = '';
                        @endphp
                    @elseif($total_ctas_SVC < ( count($delegaciones) - 1 ) )
                        @php
                            $guion = '-';
                            $color = 'warning';
                            $mensaje = 'No hay una cuenta por cada OOAD';
                        @endphp
                    @else
                        @php
                            $color = 'danger';
                            $mensaje = '¡Como máximo deben existir ' . count($delegaciones) . ' cuentas!';

                        @endphp
                    @endif
                    <li class="list-group-item">
                        <button type="button" class="btn btn-{{ $color }} btn-sm" data-toggle="tooltip" data-placement="top">
                            SVC <span class="badge badge-light"> {{ $total_ctas_SVC }}</span>
                        </button>
                        <small class="text-muted text-color-dark">{{ $mensaje }}</small>
                    </li>

                </ul>
            </div>
        <div>

    </div>
</div>
<br>
