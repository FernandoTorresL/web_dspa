<h4 class="card-title">
    <strong>
        {{--@php--}}
            {{--setlocale(LC_ALL, 'es-ES');--}}
            {{--echo strftime("%A %e %B %Y", mktime(0, 0, 0, 12, 22, 1978));--}}
            {{--// jeuves 22 diciembre 1978--}}
        {{--@endphp--}}
        Fecha: {{ date('D, d \d\e M, Y', strtotime($solicitud->fecha_solicitud_del)) }}
    </strong>
    <span class="text-muted float-right">
        @if (isset($solicitud->archivo))
            <a href="{{ $solicitud->archivo }}" target="_blank">Ver PDF</a>
        @endif
    </span>
</h4>

<div class="card border-info">
    <div class="card-header">
        <h4 class="card-title">
            <strong>
                {{ $solicitud->movimiento->name }} - {{ $solicitud->cuenta }} ({{ isset($solicitud->gpo_actual) ? $solicitud->gpo_actual->name : '' }} -> {{ isset($solicitud->gpo_nuevo) ? $solicitud->gpo_nuevo->name : '' }})</strong>
            <span class="text-muted float-right">
                {{ $solicitud->primer_apellido }}-{{ $solicitud->segundo_apellido }}-{{ $solicitud->nombre }}
            </span>
        </h4>
        <h4 class="card-title">
            <strong>{{ isset($solicitud->valija) ? 'Valija '.str_pad($solicitud->valija->delegacion->id, 2, '0', STR_PAD_LEFT).'-'.$solicitud->valija->num_oficio_ca : '(Sin Valija)' }} </strong>
            <span class="text-muted float-right">
                @if(!isset($solicitud->lote_id))
                    <a class="nav-link" href="{{ url('/ctas/solicitudes/editNC/'.$solicitud->id) }}">Editar</a>
                @endif
            </span>
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
                            {{ isset($solicitud->rechazo) ? 'Revisado/Atendido' : 'En espera de respuesta' }}
                        </span>
                        {{--</strong><span class="badge badge-pill badge-info">En revisión</span>--}}
                        <div>
                            <strong>Causa de rechazo: </strong>
                            <span class="card-text float-right @if(isset($solicitud->rechazo)) text-danger @endif">
                                {{ isset($solicitud->rechazo) ? $solicitud->rechazo->full_name : '' }}
                            </span>
                        </div>
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
                            {{ $solicitud_hasBeenModified ? $solicitud->hist_solicitudes->first()->user->name : $solicitud->user->name }}
                        </span>
                        <div>
                            <strong>Fecha de captura: </strong>
                            <span class="card-text float-right">
                                {{ date('D, d-M-Y, H:i', strtotime($solicitud->created_at)) }}
                            </span>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <strong>Modificado por: </strong>
                        <span class="card-text float-right">
                            {{ $solicitud_hasBeenModified ? $solicitud->user->name : ''}}
                        </span>
                        <div>
                            <strong>Fecha de última modificación: </strong>
                            <span class="card-text float-right">
                                {{ $solicitud_hasBeenModified ? date('D, d-M-Y, H:i', strtotime($solicitud->updated_at)) : '' }}
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
                                {{ isset($solicitud->lote) ? date('D, d-M-Y', strtotime($solicitud->lote->fecha_oficio_lote)) : '' }}
                            </span>
                        </div>
                    </li>
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
