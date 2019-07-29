<div class="col-12 ">
    <div class="card-group">
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

        <div class="card">
            <div class="card-header">
                <h5 class="card-title text-center">Cuentas por grupos</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @if($total_ctas_SSJSAV == 1 )
                        @php
                            $color = 'success';
                            $mensaje = '';
                        @endphp
                    @elseif($total_ctas_SSJDAV < 1 )
                        @php
                            $guion = '-';
                            $color = 'warning';
                            $mensaje = 'Faltan cuentas';
                        @endphp
                    @else
                        @php
                            $color = 'danger';
                            $mensaje = 'Sólo debe existir una cuenta';
                        @endphp
                    @endif
                    <li class="list-group-item">
                        <button type="button" class="btn btn-{{ $color }} btn-sm" 
                        data-toggle="tooltip" data-placement="top" 
                            title="Inventario:{{ $total_inv_SSJSAV }}
                            +Nuevas:{{ $nuevos_SSJSAV }}
                            +Cambios(a SSJSAV):{{ $cambio_nuevos_SSJSAV }}
                            -Bajas:{{ $bajas_SSJSAV }}
                            -Cambios(dejan SSJSAV):{{ $cambio_anteriores_SSJSAV }}">
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
                            $mensaje = 'Faltan cuentas';
                        @endphp
                    @else
                        @php
                            $color = 'danger';
                            $mensaje = 'Demasiadas cuentas';
                        @endphp
                    @endif
                    <li class="list-group-item">
                        <button type="button" class="btn btn-{{ $color }} btn-sm" 
                        data-toggle="tooltip" data-placement="top" 
                            title="Inventario:{{ $total_inv_SSJDAV }}
                            +Nuevas:{{ $nuevos_SSJDAV }}
                            +Cambios(a SSJDAV):{{ $cambio_nuevos_SSJDAV }}
                            -Bajas:{{ $bajas_SSJDAV }}
                            -Cambios(dejan SSJDAV):{{ $cambio_anteriores_SSJDAV }}">
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
                            $mensaje = 'Faltan cuentas';
                        @endphp
                    @else
                        @php
                            $guion = '-';
                            $color = 'danger';
                            $mensaje = 'Demasiadas cuentas';
                        @endphp
                    @endif
                    <li class="list-group-item">
                        <button type="button" class="btn btn-{{ $color }} btn-sm" 
                        data-toggle="tooltip" data-placement="top" 
                            title="Inventario:{{ $total_inv_SSJOFA }}
                            +Nuevas:{{ $nuevos_SSJOFA }}
                            +Cambios(a SSJOFA):{{ $cambio_nuevos_SSJOFA }}
                            -Bajas:{{ $bajas_SSJOFA }}
                            -Cambios(dejan SSJOFA):{{ $cambio_anteriores_SSJOFA }}">
                            SSJOFA <span class="badge badge-light">{{ $total_ctas_SSJOFA }}</span>
                        </button>
                        <small class="text-muted text-color-dark">{{ $mensaje }}</small>
                    </li>

                    <li class="list-group-item">
                        <button type="button" class="btn btn-primary btn-sm" 
                        data-toggle="tooltip" data-placement="top" 
                            title="Inventario:{{ $total_inv_SSCONS }}
                            +Nuevas:{{ $nuevos_SSCONS }}
                            +Cambios(a SSCONS):{{ $cambio_nuevos_SSCONS }}
                            -Bajas:{{ $bajas_SSCONS }}
                            -Cambios(dejan SSCONS):{{ $cambio_anteriores_SSCONS }}">
                            SSCONS <span class="badge badge-light">{{ $total_ctas_SSCONS }}</span>
                        </button>
                    </li>

                    <li class="list-group-item">
                        <button type="button" class="btn btn-primary btn-sm" 
                        data-toggle="tooltip" data-placement="top" 
                            title="Inventario:{{ $total_inv_SSADIF }}
                            +Nuevas:{{ $nuevos_SSADIF }}
                            +Cambios(a SSADIF):{{ $cambio_nuevos_SSADIF }}
                            -Bajas:{{ $bajas_SSADIF }}
                            -Cambios(dejan SSADIF):{{ $cambio_anteriores_SSADIF }}">
                            SSADIF <span class="badge badge-light">{{ $total_ctas_SSADIF }}</span>
                        </button>
                    </li>
                    
                    <li class="list-group-item">
                        <button type="button" class="btn btn-primary btn-sm" 
                        data-toggle="tooltip" data-placement="top" 
                            title="Inventario:{{ $total_inv_SSOPER }}
                            +Nuevas:{{ $nuevos_SSOPER }}
                            +Cambios(a SSOPER):{{ $cambio_nuevos_SSOPER }}
                            -Bajas:{{ $bajas_SSOPER }}
                            -Cambios(dejan SSOPER):{{ $cambio_anteriores_SSOPER }}">
                            SSOPER <span class="badge badge-light"> {{ $total_ctas_SSOPER }}</span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title text-center">Cuentas por tipo</h5>
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
                            $mensaje = 'Reemplazar por ctas personales';
                        @endphp
                    @endif
                    <li class="list-group-item">
                        <button type="button" class="btn btn-{{ $color }} btn-sm" 
                        data-toggle="tooltip" data-placement="top" 
                            title="Inventario:{{ $total_inv_Genericas }}
                            -Bajas:{{ $bajas_Genericas }}">
                            Genéricas <span class="badge badge-light"> {{ $total_ctas_Genericas }}</span>
                        </button>
                        <small class="text-muted text-color-dark">{{ $mensaje }}</small>
                    </li>

                    @if($total_ctas_SVC == 1 )
                        @php
                            $color = 'success';
                            $mensaje = '';
                        @endphp
                    @elseif($total_ctas_SVC == 0 )
                        @php
                            $guion = '-';
                            $color = 'warning';
                            $mensaje = 'Revisar';
                        @endphp
                    @else
                        @php
                            $color = 'danger';
                            $mensaje = 'Debe existir sólo una';
                        @endphp
                    @endif
                    <li class="list-group-item">
                        <button type="button" class="btn btn-{{ $color }} btn-sm" 
                        data-toggle="tooltip" data-placement="top" 
                            title="Inventario:{{ $total_inv_SVC }}
                            +Nuevas:{{ $nuevos_SVC }}
                            +Cambios(a SVC):{{ $cambio_nuevos_SVC }}
                            -Bajas:{{ $bajas_SVC }}
                            -Cambios(dejan SVC):{{ $cambio_anteriores_SVC }}">
                            SVC <span class="badge badge-light"> {{ $total_ctas_SVC }}</span>
                        </button>
                        <small class="text-muted text-color-dark">{{ $mensaje }}</small>
                    </li>

                    <li class="list-group-item">
                        <button type="button" class="btn btn-primary btn-sm"
                        data-toggle="tooltip" data-placement="top" 
                            title="Inventario:{{ $total_inv_Clas }}
                            +Nuevas:{{ $nuevos_Clas }}
                            +Cambios(a Clasificación):{{ $cambio_nuevos_Clas }}
                            -Bajas:{{ $bajas_Clas }}
                            -Cambios(dejan Clasificación):{{ $cambio_anteriores_Clas }}">
                            Clasificación y Vigencia <span class="badge badge-light"> {{ $total_ctas_Clas }}</span>
                        </button>
                    </li>
                    
                    <li class="list-group-item">
                        <button type="button" class="btn btn-primary btn-sm" 
                        data-toggle="tooltip" data-placement="top" 
                            title="Inventario:{{ $total_ctas_Fisca }}">
                            Fiscalización <span class="badge badge-light"> {{ $total_ctas_Fisca }}</span>
                        </button>
                    </li>

                    <li class="list-group-item">
                        <button type="button" class="btn btn-primary btn-sm" 
                        data-toggle="tooltip" data-placement="top" 
                            title="Inventario:{{ $total_ctas_Cobranza }}">
                            Cobranza <span class="badge badge-light"> {{ $total_ctas_Cobranza }}</span>
                        </button>
                    </li>
                </ul>
            </div>
        <div>
    </div>
</div>
<br>
