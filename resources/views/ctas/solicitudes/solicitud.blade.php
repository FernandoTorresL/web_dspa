@php
    use App\Http\Helpers\Helpers;

    // If solicitud has a response... show the captured value
    $cuenta = $solicitud->cuenta;
    if ( isset($solicitud->resultado_solicitud) )
        $cuenta = $solicitud->resultado_solicitud->cuenta;

    $bolMostrarBotonEditar = false;

    $tmp_array = Helpers::set_status_sol_flow($solicitud->status_sol_id);

    $color_solicitud        = $tmp_array['color_solicitud'];
    $color_text_solicitud   = $tmp_array['color_text_solicitud'];
    $possible_status_sol    = $tmp_array['possibles_status_sol'];
@endphp

<div class="card border-info">
    <div class="card-header">
        <h5 class="card-title">
            <strong>
                Solicitud {{ $solicitud->movimiento->name }}
                <span class="text-{{ $color_text_solicitud }}">{{ $cuenta }}</span>
            </strong>
                ( {{ isset($solicitud->gpo_actual) ? $solicitud->gpo_actual->name : '' }}
                {{ isset($solicitud->gpo_nuevo) && isset($solicitud->gpo_actual) ? '->' : '' }}
                {{ isset($solicitud->gpo_nuevo) ? $solicitud->gpo_nuevo->name : '' }} )

                @if( Auth::user()->hasRole('admin_dspa') && in_array( $solicitud->status_sol_id, [1, 2, 3, 4, 5] ) )
                    @php
                        $bolMostrarBotonEditar = true;
                    @endphp
                @endif

                @if( Auth::user()->hasRole('capturista_delegacional') && in_array( $solicitud->status_sol_id, [1, 2] ) )
                    @php
                        $bolMostrarBotonEditar = true;
                    @endphp
                @endif

                @if( (Auth::user()->hasRole('capturista_cceyvd') || Auth::user()->hasRole('autorizador_cceyvd') ) && in_array( $solicitud->status_sol_id, [1, 4] ) )
                    @php
                        $bolMostrarBotonEditar = true;
                    @endphp
                @endif

                @if ($bolMostrarBotonEditar)
                    @can('editar_solicitudes_user_nc')
                        <a class="btn btn-success" href="{{ url('/ctas/solicitudes/editNC/'.$solicitud->id) }}" role="button">
                            Editar solicitud
                        </a>
                    @elsecan('editar_solicitudes_del')
                        <a class="btn btn-success" href="{{ url('/ctas/solicitudes/edit/'.$solicitud->id) }}" role="button">
                            Editar solicitud
                        </a>
                    @endcan
                @endif

            <span class="card-text float-right">
                @can('ver_timeline_solicitudes')
                    <a class="btn btn-warning btn-sm" href="{{ url('/ctas/solicitudes/timeline/'.$solicitud->id) }}">Timeline</a>
                @endcan
                <strong>Fecha en solicitud:</strong>
                {{ \Carbon\Carbon::parse($solicitud->fecha_solicitud_del)->formatLocalized('%d-%b-%Y') }}
                @if (isset($solicitud->archivo))
                    <a class="btn btn-info" href="{{ Storage::disk('public')->url($solicitud->archivo) }}" target="_blank">PDF</a>
                @endif
            </span>
        </h5>

        <div class="small">
            <span class="card-text">
                <strong>Capturada por: </strong>
                {{ $solicitud_hasBeenModified ? $solicitud->hist_solicitudes->first()->user->name : $solicitud->user->name }}
                ({{ \Carbon\Carbon::parse($solicitud->created_at)->formatLocalized('%d-%b-%Y %H:%Mh') }},
                {{ $solicitud->created_at->diffForHumans() }})
            </span>
            <span class="card-text float-right">
                <strong>Modificada por: </strong>
                {{ $solicitud_hasBeenModified ? $solicitud->user->name : ''}}
                ({{ \Carbon\Carbon::parse($solicitud->updated_at)->formatLocalized('%d-%b-%Y %H:%Mh') }},
                {{ $solicitud_hasBeenModified ? $solicitud->updated_at->diffForHumans() : '--' }})
            </span>
        </div>
    </div>

    <div class="card-group">

        <div class="card">
            <div class="card-body border-light">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <div>
                            <strong>Nombre:</strong>
                            <span class="card-text float-right">
                                {{ $solicitud->primer_apellido }}-{{ $solicitud->segundo_apellido }}-{{ $solicitud->nombre }}
                            </span>
                        </div>
                        <div>
                            <strong>CURP (Matrícula):</strong>
                            <span class="card-text float-right">
                                {{ $solicitud->curp }} ({{ $solicitud->matricula }})
                            </span>
                        </div>
                        <div>
                            <strong>Subdel (Del):</strong>
                            <span class="card-text float-right">
                                {{ str_pad($solicitud->subdelegacion->num_sub, 2, '0', STR_PAD_LEFT) }} - {{ $solicitud->subdelegacion->name }}
                                , {{ $solicitud->delegacion->name }}
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
                        <div>
                            <strong>Lote:</strong>
                            <span class="card-text float-right">
                                {{ isset($solicitud->lote) ? $solicitud->lote->num_lote : 'Sin lote asignado' }}
                                {{ isset($solicitud->lote) ? '(' . \Carbon\Carbon::parse($solicitud->lote->fecha_oficio_lote)->formatLocalized('%d-%b-%Y') . ')' : '' }}
                            </span>
                        </div>

                        <div>
                            <strong>Valija:</strong>
                            <span class="card-text float-right">
                                @if( isset($solicitud->valija_oficio) )
                                    <a target="_blank" title="{{ $solicitud->valija_oficio->num_oficio_ca }}" href="/ctas/valijas/{{ $solicitud->valija_id }}" data-placement="center">
                                        {{ $solicitud->valija->num_oficio_del . ' ('. $solicitud->valija->delegacion->name .') | ' . $solicitud->valija->num_oficio_ca }}
                                    </a>
                                @else
                                    (Sin valija)
                                @endif
                            </span>
                        </div>

                        <div class="card-text">
                            <strong>Comentario:</strong>
                            {{ isset($solicitud->comment) ? $solicitud->comment : '--' }}
                        </div>

                    </li>
                </ul>
            </div>
        </div>

    </div>

    <div class="card-footer">
        <div class="table-{{ $color_solicitud }}">
            <div>
                <strong>Estado Actual:</strong>
                <span class="badge badge-pill badge-{{ $color_text_solicitud }}">
                    {{ isset($solicitud->status_sol) ? $solicitud->status_sol->name : 'Indefinido' }}
                </span>
                <span class="text-{{ $color_text_solicitud }}">
                    {{--{{ isset($solicitud->rechazo) ? $solicitud->rechazo->full_name : '' }}--}}
                    {{ isset($solicitud->rechazo) ? $solicitud->rechazo->full_name : (isset($solicitud->resultado_solicitud) ? '/ '.(isset($solicitud->resultado_solicitud->rechazo_mainframe) ? $solicitud->resultado_solicitud->rechazo_mainframe->name : '' ) : '') }}
                </span>
            </div>

            <div>
                <strong>Observaciones Nivel Central:</strong>
                {{ isset($solicitud->final_remark) ? $solicitud->final_remark : '--' }}
            </div>

            <div>
                <strong>Observaciones Mainframe:</strong>
                @if( isset($solicitud->resultado_solicitud) && isset($solicitud->resultado_solicitud->comment) ) {{ $solicitud->resultado_solicitud->comment }} @else -- @endif
            </div>
        </div>
    </div>

</div>

<br>

@if (   ( Auth::user()->hasRole('autorizador_cceyvd')   && in_array($solicitud->status_sol_id, [4, 5]) )
        ||
        ( Auth::user()->hasRole('admin_dspa')           && in_array($solicitud->status_sol_id, [1, 2, 3, 4, 5]) )
        ||
        ( Auth::user()->hasRole('capturista_delegacional')    && in_array($solicitud->status_sol_id, [2]) )
    )

    <form action="change_status/{{ $solicitud->id }}" method="POST">
    {{ csrf_field() }}
        {{-- Si no es el capturista delegacional, puede elegir una causa de Rechazo y agregar comentarios --}}
        @if (!Auth::user()->hasRole('capturista_delegacional'))
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
        @endif

        <div class="row">
            <div class="col-sm-6">

                @if ( Auth::user()->hasRole('capturista_delegacional')    && in_array($solicitud->status_sol_id, [2]) )
                    <div class="alert alert-danger">
                        Favor de realizar las correcciones solicitadas en <em class="alert-success">("Editar Solicitud") </em>y al finalizar dar click al botón siguiente:
                    </div>
                @endif

                <div class="form-group">
                    {{-- Si el estatus no es Enviar a Revisión DSPA(1): --}}
                    @if ($solicitud->status_sol_id<>1)
                        @if (Auth::user()->hasRole('capturista_delegacional'))
                            <button type="submit" name="action" value="en_revision_dspa" class="btn btn-outline-dark" data-toggle="tooltip"
                                data-placement="top" title="Enviar solicitud a DSPA para nueva revisión">Correcciones realizadas, enviar de nuevo a Revisión DSPA
                            </button>
                        @else
                            <button type="submit" name="action" value="en_revision_dspa" class="btn btn-outline-dark" data-toggle="tooltip"
                                data-placement="top" title="Enviar solicitud a DSPA para nueva revisión">Enviar a Revisión DSPA
                            </button>
                        @endif
                    @endif

                    @if ($solicitud->status_sol_id <> 2)
                        @if (Auth::user()->hasRole('admin_dspa') || Auth::user()->hasRole('capturista_dspa') )
                            <button type="submit" name="action" value="enviar_a_correccion" class="btn btn-outline-warning" data-toggle="tooltip"
                                data-placement="top" title="Solicitar corrección a la delegación">Requiere corrección
                            </button>
                            @if ($solicitud->status_sol_id == 5)
                                <button type="submit" name="action" value="enviar_a_mainframe" class="btn btn-info" data-toggle="tooltip"
                                    data-placement="top" title="Dar VoBo a la solicitud">Asignar Lote - Enviar a Mainframe
                                </button>
                            @endif
                        @endif
                    @endif

                    {{--Si no es capturista delegacional puede pre-autorizar o rechazar...--}}
                    @if (!Auth::user()->hasRole('capturista_delegacional'))
                        @if ($solicitud->status_sol_id<>3)
                            <button type="submit" name="action" value="no_autorizar" class="btn btn-danger" data-toggle="tooltip"
                                data-placement="top" title="Rechazar solicitud">No autorizar
                            </button>
                        @endif

                        @if ($solicitud->status_sol_id<>5)
                            <button type="submit" name="action" value="autorizar" class="btn btn-primary" data-toggle="tooltip"
                                data-placement="top" title="Dar VoBo a la solicitud">Pre-autorizar
                            </button>
                        @endif

                        @php
                            $groups_ccevyd = array(env('CCEVYD_GROUP_01'), env('CCEVYD_GROUP_02'), env('CCEVYD_GROUP_03'),
                                env('CCEVYD_GROUP_04'), env('CCEVYD_GROUP_05'), env('CCEVYD_GROUP_06'),
                                env('CCEVYD_GROUP_07'));

                            $esGroupCCEVyD = false;
                            if (isset($solicitud->gpo_nuevo))
                                if (in_array($solicitud->gpo_nuevo->name, $groups_ccevyd))
                                    $esGroupCCEVyD = true;

                            if (isset($solicitud->gpo_actual))
                                if (in_array($solicitud->gpo_actual->name, $groups_ccevyd))
                                    $esGroupCCEVyD = true;
                        @endphp

                        @if ( ($solicitud->status_sol_id == 1) && $esGroupCCEVyD )
                            <button type="submit" name="action" value="pedir_vobo" class="btn btn-secondary" data-toggle="tooltip"
                                data-placement="top" title="Dar VoBo a la solicitud">Solicitar VoBo a CCEyVD
                            </button>
                        @endif
                    @endif

                </div>
            </div>
        </div>
    </form>
@endif
