<h4 class="card-title">
    <strong>
        {{ str_pad($valija->delegacion->id, 2, '0', STR_PAD_LEFT)  }} - {{ $valija->delegacion->name }}
    </strong>
    <span class="text-muted float-right">
        <a href="{{ $valija->archivo }}" target="_blank">PDF</a>
    </span>
</h4>

<div class="card border-info">
    <div class="card-header">
        <h4 class="card-title">
            <strong># del Área de Gestión: {{ $valija->num_oficio_ca }}</strong>
            <span class="text-muted float-right">
                # de solicitudes en la valija: {{ $valija->solicitudes->count() }}
            </span>
        </h4>
        <h4 class="card-title">
            <strong>Fecha de recepción: {{ $valija->fecha_recepcion_ca }}</strong>
            <span class="text-muted float-right">
                @if($valija->status == 1)
                    <a class="nav-link" href="{{ url('/ctas/valijas/editNC/'.$valija->id) }}">Editar valija</a>
                @endif
            </span>
        </h4>
    </div>

    <div class="card-group">
        <div class="card">
            <div class="card-body border-light">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Número de Oficio Delegación: </strong>
                        <span class="card-text float-right">
                            {{ $valija->num_oficio_del }}
                        </span>
                        <div>
                            <strong>Fecha del Oficio: </strong>
                            <span class="card-text float-right">
                                {{ $valija->fecha_valija_del }}
                            </span>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <strong>Status: </strong>
                        <span class="card-text float-right @if(isset($valija->rechazo)) text-success @else text-info @endif">
                            {{ isset($valija->rechazo) ? 'Cerrada' : 'Abierta' }}
                        </span>
                        <div>
                            <strong>Causa de rechazo: </strong>
                            <span class="card-text float-right @if(isset($valija->rechazo)) text-danger @endif">
                                {{ isset($valija->rechazo) ? $valija->rechazo->full_name : '' }}
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
                            {{ $valija_hasBeenModified ? $valija->hist_valijas->first()->user->name : $valija->user->name }}
                        </span>
                        <div>
                            <strong>Fecha de captura: </strong>
                            <span class="card-text float-right">
                                {{ $valija->created_at }}
                            </span>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <strong>Modificado por: </strong>
                        <span class="card-text float-right">
                            {{ $valija_hasBeenModified ? $valija->user->name : ''}}
                        </span>
                        <div>
                            <strong>Fecha de última modificación: </strong>
                            <span class="card-text float-right">
                                {{ $valija_hasBeenModified ? $valija->updated_at : ''}}
                            </span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="text-muted">
            Comentario: {{ $valija->comment }}
        </div>
    </div>

</div>

<br>
<br>
