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
                            {{ str_pad($subdelegacion->num_sub, 2, '0', STR_PAD_LEFT) }} - {{ $subdelegacion->name }}<span class="badge badge-pill badge-success"></span>
                    @endif
                        </li>
                @empty
                    <li class="list-group-item">
                        No hay subdelegaciones registradas
                    </li>
                @endforelse
            </ul>
        </div>
        <div class="card-footer">
            <small class="text-muted">
                <br>
            </small>
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
                    <button type="button" class="btn btn-{{ $color }}" 
                    data-toggle="tooltip" data-placement="top" 
                        title="Inventario:{{ $total_inventario_SSJSAV }}
                        +Nuevas:{{ $registros_nuevos_SSJSAV }}
                        +Cambios(a SSJSAV):{{ $registros_cambio_nuevos_SSJSAV }}
                        -Bajas:{{ $registros_en_baja_SSJSAV }}
                        -Cambios(dejan SSJSAV):{{ $registros_cambio_anteriores_SSJSAV }}">
                        SSJSAV <span class="badge badge-light">{{ $total_ctas_SSJSAV }}</span>
                        <small class="text-muted text-color-dark">{{ $mensaje }}</small>
                    </button>
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
                    <button type="button" class="btn btn-{{ $color }}" 
                    data-toggle="tooltip" data-placement="top" 
                        title="Inventario:{{ $total_inventario_SSJDAV }}
                        +Nuevas:{{ $registros_nuevos_SSJDAV }}
                        +Cambios(a SSJDAV):{{ $registros_cambio_nuevos_SSJDAV }}
                        -Bajas:{{ $registros_en_baja_SSJDAV }}
                        -Cambios(dejan SSJDAV):{{ $registros_cambio_anteriores_SSJDAV }}">
                        SSJDAV <span class="badge badge-light">{{ $total_ctas_SSJDAV }}</span>
                        <small class="text-muted text-color-dark">{{ $mensaje }}</small>
                    </button>
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
                    <button type="button" class="btn btn-{{ $color }}" 
                    data-toggle="tooltip" data-placement="top" 
                        title="Inventario:{{ $total_inventario_SSJOFA }}
                        +Nuevas:{{ $registros_nuevos_SSJOFA }}
                        +Cambios(a SSJOFA):{{ $registros_cambio_nuevos_SSJOFA }}
                        -Bajas:{{ $registros_en_baja_SSJOFA }}
                        -Cambios(dejan SSJOFA):{{ $registros_cambio_anteriores_SSJOFA }}">
                        SSJOFA <span class="badge badge-light">{{ $total_ctas_SSJOFA }}</span>
                        <small class="text-muted text-color-dark">{{ $mensaje }}</small>
                    </button>
                </li>

                <li class="list-group-item">
                    <button type="button" class="btn btn-primary" 
                    data-toggle="tooltip" data-placement="top" 
                        title="Inventario:{{ $total_inventario_SSCONS }}
                        +Nuevas:{{ $registros_nuevos_SSCONS }}
                        +Cambios(a SSCONS):{{ $registros_cambio_nuevos_SSCONS }}
                        -Bajas:{{ $registros_en_baja_SSCONS }}
                        -Cambios(dejan SSCONS):{{ $registros_cambio_anteriores_SSCONS }}">
                        SSCONS <span class="badge badge-light">{{ $total_ctas_SSCONS }}</span>
                    </button>
                </li>

                <li class="list-group-item">
                    <button type="button" class="btn btn-primary" 
                    data-toggle="tooltip" data-placement="top" 
                        title="Inventario:{{ $total_inventario_SSADIF }}
                        +Nuevas:{{ $registros_nuevos_SSADIF }}
                        +Cambios(a SSADIF):{{ $registros_cambio_nuevos_SSADIF }}
                        -Bajas:{{ $registros_en_baja_SSADIF }}
                        -Cambios(dejan SSADIF):{{ $registros_cambio_anteriores_SSADIF }}">
                        SSADIF <span class="badge badge-light">{{ $total_ctas_SSADIF }}</span>
                    </button>
                </li>
                
                <li class="list-group-item">
                    <button type="button" class="btn btn-primary" 
                    data-toggle="tooltip" data-placement="top" 
                        title="Inventario:{{ $total_inventario_SSOPER }}
                        +Nuevas:{{ $registros_nuevos_SSOPER }}
                        +Cambios(a SSOPER):{{ $registros_cambio_nuevos_SSOPER }}
                        -Bajas:{{ $registros_en_baja_SSOPER }}
                        -Cambios(dejan SSOPER):{{ $registros_cambio_anteriores_SSOPER }}">
                        SSOPER <span class="badge badge-light"> {{ $total_ctas_SSOPER }}</span>
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-footer">
            <small class="text-muted">Código de colores:
                <span class="badge badge-pill badge-success">Correcto</span>
                <span class="badge badge-pill badge-danger">Depurar</span>
                <span class="badge badge-pill badge-primary">Informativo</span>
            </small>
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
                    <button type="button" class="btn btn-{{ $color }}" 
                    data-toggle="tooltip" data-placement="top" 
                        title="Inventario:{{ $total_inventario_Genericas }}
                        -Bajas:{{ $registros_en_baja_Genericas }}">
                        Genéricas <span class="badge badge-light"> {{ $total_ctas_Genericas }}</span>
                    </button>
                </li>

                @if($total_ctas_SVC <= 1 )
                    @php
                        $color = 'success';
                        $mensaje = '';
                    @endphp
                @else
                    @php
                        $color = 'danger';
                        $mensaje = 'Debe existir sólo una';
                    @endphp
                @endif
                <li class="list-group-item">
                    <button type="button" class="btn btn-{{ $color }}" 
                    data-toggle="tooltip" data-placement="top" 
                        title="Inventario:{{ $total_inventario_SVC }}
                        +Nuevas:{{ $registros_nuevos_SVC }}
                        +Cambios(a SVC):{{ $registros_cambio_nuevos_SVC }}
                        -Bajas:{{ $registros_en_baja_SVC }}
                        -Cambios(dejan SVC):{{ $registros_cambio_anteriores_SVC }}">
                        SVC <span class="badge badge-light"> {{ $total_ctas_SVC }}</span>
                    </button>
                </li>

                <li class="list-group-item">
                    <button type="button" class="btn btn-primary" 
                    data-toggle="tooltip" data-placement="top" 
                        title="Inventario:{{ $total_inventario_Clas }}
                        +Nuevas:{{ $registros_nuevos_Clas }}
                        +Cambios(a Clasificación):{{ $registros_cambio_nuevos_Clas }}
                        -Bajas:{{ $registros_en_baja_Clas }}
                        -Cambios(dejan Clasificación):{{ $registros_cambio_anteriores_Clas }}">
                        Clasificación y Vigencia <span class="badge badge-light"> {{ $total_ctas_Clas }}</span>
                    </button>
                </li>
                
                <li class="list-group-item">
                    <button type="button" class="btn btn-primary" 
                    data-toggle="tooltip" data-placement="top" 
                        title="Inventario:{{ $total_inventario_Fisca }}
                        +Nuevas:{{ $registros_nuevos_Fisca }}
                        +Cambios(a Fiscalización):{{ $registros_cambio_nuevos_Fisca }}
                        -Bajas:{{ $registros_en_baja_Fisca }}
                        -Cambios(dejan Fiscalización):{{ $registros_cambio_anteriores_Fisca }}">
                        Fiscalización <span class="badge badge-light"> {{ $total_ctas_Fisca }}</span>
                    </button>
                </li>

                <li class="list-group-item">
                    <button type="button" class="btn btn-primary" 
                    data-toggle="tooltip" data-placement="top" 
                        title="Inventario:{{ $total_ctas_Cobranza }}">
                        Cobranza <span class="badge badge-light"> {{ $total_ctas_Cobranza }}</span>
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-footer">
            <small class="text-muted">Total de cuentas
                <span class="badge badge-pill badge-info">{{ $total_ctas }}</span>
            </small>
        </div>
    </div>
</div>
</div>

<div class="col-10 col-md-12">
    <br>
</div>
