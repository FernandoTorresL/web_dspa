

@php
    use App\Http\Helpers\Helpers;
@endphp

<div class="col-12">
    <div class="card-group">

        <div class="card">
            <div class="card-header">
                <h6 class="card-title text-center">
                    @if ($delegacion_a_consultar->id == 0)
                        Nacional
                        <p>
                            Todas las delegaciones
                        </p>
                    @else
                        {{ $delegacion_a_consultar->name }}
                        <p>
                            Subdelegaciones
                        </p>
                    @endif
                </h6>
            </div>

            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @forelse($subdelegaciones as $subdelegacion)
                        @if ($subdelegacion->num_sub <> 0)
                            <li class="list-group-item text-truncate">
                                {{ str_pad($subdelegacion->num_sub, 2, '0', STR_PAD_LEFT) }}
                                - {{ $subdelegacion->name }}
                            </li>
                        @endif
                    @empty

                    @endforelse
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="card-title text-center">
                    Conteo de
                    <p>
                        grupos
                    </p>
                </h6>
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

                    {{-- Cuentas INFONAVIT --}}
                    <li class="list-group-item">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top">
                            INFONAVIT <span class="badge badge-light"> {{ $total_ctas_Infonavit }}</span>
                        </button>
                    </li>

                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="card-title text-center">
                    Conteo de
                    <p>
                        grupos
                    </p>
                </h6>
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

                    {{-- Cuentas SSJURI --}}
                    <li class="list-group-item">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top">
                            SSJURI <span class="badge badge-light"> {{ $total_ctas_SSJURI }}</span>
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

                    {{-- Cuentas TTD --}}
                    <li class="list-group-item">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top">
                            TTD <span class="badge badge-light"> {{ $total_ctas_TTD }}</span>
                        </button>
                    </li>

                </ul>
            </div>
        <div>

    </div>
</div>
<br>
