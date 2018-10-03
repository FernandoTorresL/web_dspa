<div class="card-group">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Subdelegaciones</h5>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @forelse($subdelegaciones as $subdelegacion)
                    <li class="list-group-item text-truncate">
                        {{ $subdelegacion->num_sub }} - {{ $subdelegacion->name }}<span class="badge badge-pill badge-success"></span>
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
                Próximamente: Cifras de conteo por subdelegacion
            </small>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Cuentas por grupos</h5>
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
                        $mensaje = 'Sólo debe existir un Supervisor';
                    @endphp
                @endif
                <li class="list-group-item">
                    SSJSAV <span class="float-right badge badge-pill badge-{{ $color }}">{{ $total_ctas_SSJSAV }}</span>
                    <small class="text-muted">{{ $mensaje }}</small>
                </li>

                @if($total_ctas_SSJDAV == count($subdelegaciones) )
                    @php
                        $color = 'success';
                        $mensaje = '';
                    @endphp
                @elseif($total_ctas_SSJDAV <= count($subdelegaciones) )
                    @php
                        $guion = '-';
                        $color = 'warning';
                        $mensaje = 'Faltan cuentas';
                    @endphp
                @else
                    @php
                        $color = 'danger';
                        $mensaje = 'Demasiados Jefes de Depto.';
                    @endphp
                @endif
                <li class="list-group-item">
                    SSJDAV <span class="float-right badge badge-pill badge-{{ $color }}">{{ $total_ctas_SSJDAV }}</span>
                    <small class="text-muted">{{ $mensaje }}</small>
                </li>

                @if($total_ctas_SSJOFA == count($subdelegaciones) )
                    @php
                        $guion = '-';
                        $color = 'success';
                        $mensaje = '';
                    @endphp
                @elseif($total_ctas_SSJOFA <= count($subdelegaciones) )
                    @php
                        $guion = '-';
                        $color = 'warning';
                        $mensaje = 'Faltan cuentas';
                    @endphp
                @else
                    @php
                        $guion = '-';
                        $color = 'danger';
                        $mensaje = 'Demasiados Jefes de Oficina.';
                    @endphp
                @endif
                <li class="list-group-item">
                    SSJOFA <span class="float-right badge badge-pill badge-{{ $color }}">{{ $total_ctas_SSJOFA }}</span>
                    <p><small class="text-muted">{{ $mensaje }}</small></p>
                </li>
                <li class="list-group-item">
                    SSCONS <span class="float-right badge badge-pill badge-primary">{{ $total_ctas_SSCONS }}</span>
                </li>
                <li class="list-group-item">
                    SSADIF <span class="float-right badge badge-pill badge-primary">{{ $total_ctas_SSADIF }}</span>
                </li>
                <li class="list-group-item">
                    SSOPER <span class="float-right badge badge-pill badge-primary">{{ $total_ctas_SSOPER }}</span>
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
            <h5 class="card-title">Cuentas por tipo</h5>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @if($total_ctas_genericas == 0 )
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
                    Genéricas <span class="float-right badge badge-pill badge-{{ $color }}">{{ $total_ctas_genericas }}</span>
                    <small class="text-muted">{{ $mensaje }}</small>
                </li>
                @if($total_ctas_svc <= 1 )
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
                    SVC <span class="float-right badge badge-pill badge-{{ $color }}">{{ $total_ctas_svc }}</span>
                    <small class="text-muted">{{ $mensaje }}</small>
                </li>
                <li class="list-group-item">
                    Clasificación <span class="float-right badge badge-pill badge-primary">{{ $total_ctas_clas }}</span>
                </li>
                <li class="list-group-item">
                    Fiscalización <span class="float-right badge badge-pill badge-primary">{{ $total_ctas_fisca }}</span>
                </li>
            </ul>
        </div>

        <div class="card-footer">
            <small class="text-muted">Total de cuentas <span class="float-right badge badge-pill badge-info">{{ $total_ctas->count() }}</span></small>
        </div>
    </div>
</div>

<div class="col-10 col-md-12">
    <br>
</div>
