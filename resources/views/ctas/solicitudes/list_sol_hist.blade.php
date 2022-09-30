@extends('layouts.app')

@section('title', 'Listado Solicitudes Históricas')

@section('content')

@php
    use App\Http\Helpers\Helpers;
    use App\Solicitud;
    $var = 0;
@endphp
    <span>
        <a class="btn btn-default" href="{{ url('/') }}">Inicio</a>
        <a class="btn btn-default" href="{{ url()->previous() }}">Regresar</a>
    </span>

@if(Auth::check())
    <div class="card text-white bg-danger">
        <div class="card-header">
            <span class="h4">Historial de cambios</span>
        </div>
    </div>
    <br>
    <h5 class="text-danger">Cambios localizados: {{ $list_sol_historicas->count() }} </h5>
    <table class="table table-sm table-striped">
        <thead>
            <tr class="small">
                <th scope="col">#</th>
                <th scope="col">Fecha</th>
                <th scope="col">Capturado por</th>
                <th scope="col">Lote</th>
                <th scope="col">Valija</th>
                <th scope="col">Del Subdel</th>
                <th scope="col">Nombre Completo</th>
                <th scope="col">CURP (Matrícula)</th>
                <th scope="col">Cuenta</th>
                <th scope="col">Mov</th>
                <th scope="col">Coment.</th>
                <th scope="col">Observ.</th>
                <th scope="col">PDF</th>
                <th scope="col">Causa Rechazo</th>
                <th scope="col">Estatus</th>
            </tr>
        </thead>
        <tbody>

    @forelse( $list_sol_historicas as $solicitud_historica )
        @php
            $var += 1;
            $tmp_array = Helpers::set_status_sol_flow($solicitud_historica->status_sol_id);
            $color_solicitud        = $tmp_array['color_solicitud'];
            $color_text_solicitud   = $tmp_array['color_text_solicitud'];
            $possible_status_sol    = $tmp_array['possibles_status_sol'];
        @endphp
            <tr class="table-{{$color_solicitud}}" scope="row">
                <td class="small">
                    <strong>{{ $var }}</strong>
                </td>
                <td class="small">
                    @if ($var == 1)
                        {{ Helpers::format_datetime_short($solicitud_actual->created_at) }}
                    @else
                        {{ Helpers::format_datetime_short($fecha_modificacion) }}
                    @endif
                    @php
                        $fecha_modificacion = $solicitud_historica->updated_at;
                    @endphp
                </td>
                <td class="small">
                        {{ $solicitud_historica->user->name }}
                </td>
                <td class="small">{{ isset($solicitud_historica->lote) ? $solicitud_historica->lote->num_lote : '--' }}</td>
                <td class="small">
                    @if( isset($solicitud_historica->valija) )
                        <a target="_blank" title="{{ $solicitud_historica->valija->num_oficio_ca }}" href="/ctas/valijas/{{ $solicitud_historica->valija_id }}" data-placement="center">
                            <span>{{ $solicitud_historica->valija->num_oficio_del }}</span>
                            <span>{{ $solicitud_historica->valija->num_oficio_ca }}</span>
                        </a>
                    @else
                        {{ '--' }}
                    @endif
                </td>
                <td class="small">
                    @php
                        $del_name = $del_num = NULL;

                        // If there's 'valija' and valija.delegacion is different to solicitud.delegacion, show also (valija.delegacion)
                        if ( ( isset($solicitud_historica->valija) && ($solicitud_historica->valija->delegacion_id <> $solicitud_historica->delegacion->id) ) ) {
                            $del_name = 'Valija(' . $solicitud_historica->valija->delegacion->name .') ';
                        }
                        $del_num = str_pad($solicitud_historica->delegacion_id, 2, '0', STR_PAD_LEFT);
                    @endphp
                    <span>{{ $del_name }}</span>
                    <span>{{ $solicitud_historica->delegacion->id . ' -'}}</span>
                    {{$solicitud_historica->subdelegacion->name }}
                </td>
                <td class="small">
                    <span>{{ $solicitud_historica->primer_apellido }}-</span>
                    <span>{{ $solicitud_historica->segundo_apellido }}-</span>
                    {{ $solicitud_historica->nombre}}
                </td>
                <td class="small">
                    <span>{{ $solicitud_historica->curp }}</span>
                    ({{ $solicitud_historica->matricula }})</td>
                <td class="small">
                    <a target="_blank" alt="Ver/Editar" href="/ctas/solicitudes/{{ $solicitud_historica->solicitud_id }}">
                        <button type="button" class="btn btn-primary btn-sm text-monospace" data-toggle="tooltip" data-placement="right"
                            title="Click para ver detalle...">
                            {{ $solicitud_historica->cuenta }}
                        </button>
                    </a>
                </td>
                <td class="small">
                    <span>{{ $solicitud_historica->movimiento->name }}</span>
                    <span>{{ isset($solicitud_historica->gpo_actual->name) ? $solicitud_historica->gpo_actual->name : '--' }}</span>
                    {{ isset($solicitud_historica->gpo_nuevo->name) ? $solicitud_historica->gpo_nuevo->name : '' }}
                </td>
                <td class="small">{{ isset($solicitud_historica->comment) ? $solicitud_historica->comment : '--' }}</td>
                <td class="small">{{ isset($solicitud_historica->final_remark) ? $solicitud_historica->final_remark : '--' }}</td>
                <td class="small">
                    @if (isset($solicitud_historica->archivo))
                    <a class="info" href="{{ Storage::disk('public')->url($solicitud_historica->archivo) }}" target="_blank">PDF</a>
                    @endif
                </td>
                <td class="small">{{ isset($solicitud_historica->rechazo) ? $solicitud_historica->rechazo->full_name : '--' }}</td>
                {{-- <td class="text-{{ $color_solicitud }}"> --}}
                <td class="small">
                    <a target="_blank" alt="Ver/Editar" href="/ctas/solicitudes/{{ $solicitud_historica->id }}">
                        <button type="button" class="btn btn-outline-{{ $color_text_solicitud }} btn-sm" data-toggle="tooltip" data-placement="top"
                            title={{ isset($solicitud_historica->status_sol) ? '"' . $solicitud_historica->status_sol->description . '"' : '""' }}>
                            {{ isset($solicitud_historica->status_sol) ? $solicitud_historica->status_sol->name : 'No definido aún' }}
                        </button>
                    </a>
                </td>
                <br>
            </tr>
    @empty
        <br>
        <div class="text-danger">No hay cambios para esta solicitud</div>
        <br>
        <hr>
    @endforelse

    {{-- Código para mostrar el estatus actual --}}
            <tr></tr>
            <tr></tr>
        @php
            $tmp_array = Helpers::set_status_sol_flow($solicitud_actual->status_sol_id);
            $color_solicitud        = $tmp_array['color_solicitud'];
            $color_text_solicitud   = $tmp_array['color_text_solicitud'];
            $possible_status_sol    = $tmp_array['possibles_status_sol'];
        @endphp
            <tr class="table-{{$color_solicitud}}" scope="row">
                <td class="small">
                    <strong>Actual</strong>
                </td>
                <td class="small">
                    {{ Helpers::format_datetime_short($solicitud_actual->updated_at) }}
                </td>
                <td class="small">
                    {{ $solicitud_actual->user->name }}
                </td>
                <td class="small">{{ isset($solicitud_actual->lote) ? $solicitud_actual->lote->num_lote : '--' }}</td>
                <td class="small">
                @if( isset($solicitud_actual->valija) )
                    <a target="_blank" title="{{ $solicitud_actual->valija->num_oficio_ca }}" href="/ctas/valijas/{{ $solicitud_actual->valija_id }}" data-placement="center">
                        <span>{{ $solicitud_actual->valija->num_oficio_del }}</span>
                        <span>{{ $solicitud_actual->valija->num_oficio_ca }}</span>
                    </a>
                @else
                    {{ '--' }}
                @endif
                </td>
                <td class="small">
                    @php
                        $del_name = $del_num = NULL;

                        // If there's 'valija' and valija.delegacion is different to solicitud.delegacion, show also (valija.delegacion)
                        if ( ( isset($solicitud_actual->valija) && ($solicitud_actual->valija->delegacion_id <> $solicitud_actual->delegacion->id) ) ) {
                            $del_name = 'Valija(' . $solicitud_actual->valija->delegacion->name .') ';
                        }
                        $del_num = str_pad($solicitud_actual->delegacion_id, 2, '0', STR_PAD_LEFT);
                    @endphp
                    <span>{{ $del_name }}</span>
                    <span>{{ $solicitud_actual->delegacion->id . ' -'}}</span>
                    {{ $solicitud_actual->subdelegacion->name }}
                </td>
                <td class="small">
                    <span>{{ $solicitud_actual->primer_apellido }}-</span>
                    <span>{{ $solicitud_actual->segundo_apellido }}-</span>
                    {{ $solicitud_actual->nombre}}
                </td>
                <td class="small">
                    <span>{{ $solicitud_actual->curp }}</span>
                    ({{ $solicitud_actual->matricula }})</td>
                <td class="small">
                    <a target="_blank" alt="Ver/Editar" href="/ctas/solicitudes/{{ $solicitud_actual->id }}">
                        <button type="button" class="btn btn-primary btn-sm text-monospace" data-toggle="tooltip" data-placement="right"
                            title="Click para ver detalle...">
                            {{ $solicitud_actual->cuenta }}
                        </button>
                    </a>
                </td>
                <td class="small">
                    <span>{{ $solicitud_actual->movimiento->name }}</span>
                    <span>{{ isset($solicitud_actual->gpo_actual->name) ? $solicitud_actual->gpo_actual->name : '--' }}</span>
                    {{ isset($solicitud_actual->gpo_nuevo->name) ? $solicitud_actual->gpo_nuevo->name : '' }}
                </td>
                <td class="small">{{ isset($solicitud_actual->comment) ? $solicitud_actual->comment : '--' }}</td>
                <td class="small">{{ isset($solicitud_actual->final_remark) ? $solicitud_actual->final_remark : '--' }}</td>
                <td class="small">
                    @if (isset($solicitud_actual->archivo))
                    <a class="info" href="{{ Storage::disk('public')->url($solicitud_actual->archivo) }}" target="_blank">PDF</a>
                    @endif
                </td>
                <td class="small">{{ isset($solicitud_actual->rechazo) ? $solicitud_actual->rechazo->full_name : '--' }}</td>
                {{-- <td class="text-{{ $color_solicitud }}"> --}}
                <td class="small">
                    <a target="_blank" alt="Ver/Editar" href="/ctas/solicitudes/{{ $solicitud_actual->id }}">
                        <button type="button" class="btn btn-outline-{{ $color_text_solicitud }} btn-sm" data-toggle="tooltip" data-placement="top"
                            title={{ isset($solicitud_actual->status_sol) ? '"' . $solicitud_actual->status_sol->description . '"' : '""' }}>
                            {{ isset($solicitud_actual->status_sol) ? $solicitud_actual->status_sol->name : 'No definido aún' }}
                        </button>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
@endif
    
</div>
@endsection
