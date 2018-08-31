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
                        <strong>Delegación: </strong><span class="card-text float-right">{{ $solicitud->delegacion->id }} - {{ $solicitud->delegacion->name }}</span>
                        <div>
                            <strong>Subdelegación: </strong><span class="card-text float-right text-truncate">{{ $solicitud->subdelegacion->num_sub }} - {{ $solicitud->subdelegacion->name }}</span>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <strong>Status: </strong><span class="badge badge-pill badge-info">En revisión</span>
                        <div>
                            <strong>Causa de rechazo: </strong>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-body border-light">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Capturado por: </strong><span class="card-text float-right">{{ $solicitud->user->name }}</span>
                        <div>
                            <strong>Fecha de captura: </strong><span class="card-text float-right">{{ $solicitud->created_at }}</span>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <strong>Modificado por: </strong><span class="card-text float-right">{{ $solicitud->user->name }}</span>
                        <div>
                            <strong>Fecha de última modificación: </strong><span class="card-text float-right">{{ $solicitud->updated_at }}</span>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <strong>Lote: </strong><span class="badge badge-pill badge-info">Por definir</span>
                        <div>
                            <strong>Fecha de envío a Mainframe: </strong>
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
