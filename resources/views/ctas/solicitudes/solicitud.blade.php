<h4 class="card-title">
    <strong>
        @php
            use Carbon\Carbon;
            setlocale(LC_TIME, 'es-ES');
            \Carbon\Carbon::setUtf8(false);

            $estatus_solicitud = $solicitud->status_sol_id;

            switch($estatus_solicitud) {
                case 1:     $color = 'light';       $color_text = 'dark';       $possible_status = [ 2, 3, 4, 5 ]; break;
                case 2:     $color = 'warning';     $color_text = 'warning';    $possible_status = [ 1 ]; break;
                case 3:     $color = 'danger';      $color_text = 'danger';     $possible_status = [ 1, 2 ]; break;
                case 4:     $color = 'secondary';   $color_text = 'secondary';  $possible_status = [ 3, 5 ]; break;
                case 5:     $color = 'primary';     $color_text = 'primary';    $possible_status = [ ]; break;
                case 6:     $color = 'info';        $color_text= 'dark';        $possible_status = [ 7, 8, 9 ]; break;
                case 7:     $color = 'danger';      $color_text = 'danger';     $possible_status = [ 0 ]; break;
                case 8:     $color = 'success';     $color_text = 'success';    $possible_status = [ 0 ]; break;
                case 9:     $color = 'secondary';   $color_text = 'secondary';  $possible_status = [ 3, 7, 8 ]; break;
                default:    $color = 'secondary';
            }

            // If solicitud has a response...
            if ( isset($solicitud->resultado_solicitud) )
                $cuenta = $solicitud->resultado_solicitud->cuenta;
            else {
                //...show the captured value
                $cuenta = $solicitud->cuenta;
            }
        @endphp

        {{ $solicitud->movimiento->name }}

        <h4 class="badge badge-pill badge-{{$color_text}}">{{ $cuenta }}</h4>

        ({{ isset($solicitud->gpo_actual) ? $solicitud->gpo_actual->name : '' }}
        {{ isset($solicitud->gpo_nuevo) && isset($solicitud->gpo_actual) ? '->' : '' }}
        {{ isset($solicitud->gpo_nuevo) ? $solicitud->gpo_nuevo->name : '' }})
        -
        @if( isset($solicitud->valija_oficio) )
            <a target="_blank" title="{{ $solicitud->valija_oficio->num_oficio_ca }}" href="/ctas/valijas/{{ $solicitud->valija_id }}" data-placement="center">
            {{ 'Valija ('.str_pad($solicitud->valija->delegacion->id, 2, '0', STR_PAD_LEFT). ') - ' . $solicitud->valija->num_oficio_del . ' (' . $solicitud->valija->num_oficio_ca . ')' }}
            </a>
        @else
            (Sin valija)
        @endif
    </strong>

        @can('ver_timeline_solicitudes')
            <a class="" href="{{ url('/ctas/solicitudes/timeline/'.$solicitud->id) }}">Ver Timeline</a>
        @endcan
        <span class="text-muted float-right">
            @if (isset($solicitud->archivo))
                <a href="{{ $solicitud->archivo }}" target="_blank">Ver PDF</a>
            @endif
        </span>
</h4>

<div class="card border-info">
    <div class="card-header">
        <h5 class="card-title">
            <span class="text-muted">
                {{ $solicitud->primer_apellido }}-{{ $solicitud->segundo_apellido }}-{{ $solicitud->nombre }}
            </span>
            <span class="text-muted float-right">
            {{ \Carbon\Carbon::parse($solicitud->fecha_solicitud_del)->formatLocalized('%d de %B, %Y') }}
            @if( ( !isset($solicitud->lote_id) && (!isset($solicitud->rechazo) && !isset($solicitud->resultado_solicitud->rechazo_mainframe)) || Auth::user()->id == 1 ) )
                @can('editar_solicitudes_user_nc')
                    <a class="btn btn-success btn-sm" href="{{ url('/ctas/solicitudes/editNC/'.$solicitud->id) }}" role="button">
                        Editar solicitud
                    </a>
                @elsecan('editar_solicitudes_del')
                    <a class="btn btn-success btn-sm" href="{{ url('/ctas/solicitudes/edit/'.$solicitud->id) }}" role="button">
                        Editar solicitud
                    </a>
                @endcan
            @endif
            </span>
        </h5>
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
                        <strong>Status:</strong>
                        <span class="card-text float-right text-{{$color_text}}">
                            <h5>
                                <span class="badge badge-pill badge-{{$color_text}}">
                                    {{ isset($solicitud->status_sol) ? $solicitud->status_sol->name : 'Indefinido' }}
                                </span>
                            </h5>
                        </span>

                        <div>
                            <strong>Causa de rechazo: </strong>
                            <span class="card-text float-right @if(isset($solicitud->rechazo) || isset($solicitud->resultado_solicitud->rechazo_mainframe)) text-danger @endif">
                                {{--{{ isset($solicitud->rechazo) ? $solicitud->rechazo->full_name : '' }}--}}
                                {{ isset($solicitud->rechazo) ? $solicitud->rechazo->full_name : (isset($solicitud->resultado_solicitud) ? '/ '.(isset($solicitud->resultado_solicitud->rechazo_mainframe) ? $solicitud->resultado_solicitud->rechazo_mainframe->name : '' ) : '') }}
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
                            <strong></strong>
                            <span class="card-text float-right">
                                {{ \Carbon\Carbon::parse($solicitud->created_at)->formatLocalized('%d de %B, %Y %H:%Mh') }}
                                <span class="small">({{ $solicitud->created_at->diffForHumans() }})</span>
                            </span>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <strong>Modificado por: </strong>
                        <span class="card-text float-right">
                            {{ $solicitud_hasBeenModified ? $solicitud->user->name : ''}}
                        </span>
                        <div>
                            <strong></strong>
                            <span class="card-text float-right">
                                <span class="small">
                                    {{ $solicitud_hasBeenModified ? $solicitud->updated_at->diffForHumans() : '--' }}
                                </span>
                            </span>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <strong>Lote: </strong>
                        <span class="card-text float-right @if(isset($solicitud->lote->num_lote)) text-info @else text-primary @endif">
                            {{ isset($solicitud->lote) ? $solicitud->lote->num_lote : 'Sin lote asignado' }}
                        </span>
                        <div>
                            <strong>Fecha de envío a Mainframe: </strong>
                            <span class="card-text float-right @if(isset($solicitud->lote)) text-info @endif">
                                {{ isset($solicitud->lote) ? \Carbon\Carbon::parse($solicitud->lote->fecha_oficio_lote)->formatLocalized('%d de %B, %Y') : '' }}
                            </span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="text-muted">
            Comentario: {{ isset($solicitud->comment) ? $solicitud->comment : '--' }}
        </div>
        <div class="@if( (isset($solicitud->rechazo) && isset($solicitud->final_remark)) 
            || $estatus_solicitud = 3 || $estatus_solicitud = 7) text-danger @else text-primary @endif">
            Observaciones Nivel Central: {{ isset($solicitud->final_remark) ? $solicitud->final_remark : '--' }}
        </div>
        <div class="text-danger">
            Observaciones Mainframe:
            @if( isset($solicitud->resultado_solicitud) && isset($solicitud->resultado_solicitud->comment) ) {{ $solicitud->resultado_solicitud->comment }} @else -- @endif
        </div>
    </div>
</div>

<br>

@if($solicitud->status_sol_id == 4 )

    @can('autorizar_solicitudes_cceyvd')

        <form action="change_status/{{ $solicitud->id }}" method="POST">
        {{ csrf_field() }}

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="rechazo">Causa de Rechazo</label>
                        <select class="form-control @if($errors->has('rechazo')) is-invalid @endif" id="rechazo" name="rechazo">
                            <option value="" selected>0 - Sin rechazo</option>
                            @if (!isset($solicitud->rechazo->id))
                                @php
                                    $id_rechazo = 0;
                                @endphp
                            @else
                                @php
                                    $id_rechazo = $solicitud->rechazo->id;
                                @endphp
                            @endif
                            @forelse($rechazos as $rechazo)
                                @php
                                    $rechazo->id == old('rechazo', $id_rechazo) ? $str_check = 'selected' : $str_check = '';
                                @endphp
                                <option value="{{ $rechazo->id }}" {{ $str_check }}>{{ $rechazo->id }} - {{ $rechazo->full_name }}</option>
                            @empty
                            @endforelse
                        </select>
                        @if ($errors->has('rechazo'))
                            @foreach($errors->get('rechazo') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8">
                    <div class="input-group mb-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Observaciones Nivel Central</p></span>

                        </div>
                        <textarea class="form-control" id="final_remark" name="final_remark" placeholder="(Opcional)" rows="2">{{ old('final_remark', $solicitud->final_remark) }}</textarea>
                    </div>
                </div>
            </div>
        <!--
            <div class="row">
                <div class="col-sm-8">
                    <div class="input-group mb-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Observaciones al Autorizar/No Autorizar</span>
                        </div>
                        <textarea class="form-control" id="final_remark" name="final_remark" placeholder="(Opcional)" rows="2">{{ old('final_remark') }}</textarea>
                    </div>
                </div>
            </div> -->

            <div class="row">
                <div class="col-sm-8">
                    <div class="input-group mb-4">
                        <button type="submit" name="action" value="no_autorizar" class="btn btn-danger">No autorizar</button>
                        <button type="submit" name="action" value="autorizar" class="btn btn-primary">Autorizar (CCEyVD)</button>
                    </div>
                </div>
            </div>
        </form>
    @endcan
@endif
