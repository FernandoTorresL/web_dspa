<div class="card border-info">
    <div class="card-header">
        <h4 class="card-title">
            <strong>{{ $solicitud->cuenta }} </strong>
            <span class="text-muted float-right">
                {{ $solicitud->primer_apellido }}-{{ $solicitud->segundo_apellido }}-{{ $solicitud->nombre }}
            </span>
        </h4>
        <h4 class="card-title">
            <strong>{{ $solicitud->movimiento->name }} </strong>
            <span class="text-muted float-right"> {{ $solicitud->gpo_actual->name }} -> {{ $solicitud->gpo_nuevo->name }}</span>
        </h4>
    </div>

    <div class="card-group">
        <div class="card">
            <div class="card-body border-light">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>CURP: </strong><span class="card-text float-right">{{ $solicitud->curp }}</span>
                        <div>
                            <strong>Matrícula: </strong><span class="card-text float-right">{{ $solicitud->matricula }}</span>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div>
                            <strong>Delegación: </strong>
                            <span class="card-text float-right">
                                {{ str_pad($solicitud->delegacion->id, 2, '0', STR_PAD_LEFT)  }} - {{ $solicitud->delegacion->name }}
                            </span>
                        </div>
                        <div>
                            <strong>Subdelegación: </strong><span class="card-text float-right text-truncate">{{ str_pad($solicitud->subdelegacion->num_sub, 2, '0', STR_PAD_LEFT) }} - {{ $solicitud->subdelegacion->name }}</span>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <strong>Status: </strong>
                        <span class="card-text float-right @if(isset($solicitud->rechazo)) text-success @else text-info @endif">
                            {{ isset($solicitud->rechazo) ? 'Atendido' : 'En espera de respuesta' }}
                        </span>
                        {{--</strong><span class="badge badge-pill badge-info">En revisión</span>--}}
                        <div>
                            <strong>Causa de rechazo: </strong>
                            <span class="card-text float-right @if(isset($solicitud->rechazo)) text-danger @endif">
                                {{ isset($solicitud->rechazo) ? $solicitud->rechazo->full_name : '' }}
                            </span>
                        </div>
                        {{--$solicitud->has('rechazo') ? $solicitud->rechazo->full_name : ''--}}
                    </li>
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-body border-light">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Capturado por: </strong>
                        <span class="card-text float-right">
                            {{ $solicitud->user->name }}
                        </span>
                        <div>
                            <strong>Fecha de captura: </strong><span class="card-text float-right">{{ $solicitud->created_at }}</span>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <strong>Modificado por: </strong>
                        <span class="card-text float-right">
                            {{ $solicitud->user->name }}
                        </span>
                        <div>
                            <strong>Fecha de última modificación: </strong>
                            <span class="card-text float-right">
                                {{ $solicitud->updated_at }}
                            </span>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <strong>Lote: </strong>
                        <span class="card-text float-right @if(isset($solicitud->lote->num_lote)) text-info @else text-warning @endif">
                            {{ isset($solicitud->lote) ? $solicitud->lote->num_lote : 'Sin lote asignado' }}
                        </span>
                        <div>
                            <strong>Fecha de envío a Mainframe: </strong>
                            <span class="card-text float-right @if(isset($solicitud->lote)) text-info @endif">
                                {{ isset($solicitud->lote) ? $solicitud->lote->fecha_oficio_lote : '' }}
                            </span>
                        </div>
                    </li>
                    {{--<li class="list-group-item">--}}
                        {{--<a class="nav-link" href="{{ $solicitud->archivo }}">Ver PDF</a>--}}
                    {{--</li>--}}
                </ul>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="text-muted">
            Comentario: {{ $solicitud->comment }}
        </div>
    </div>

</div>
