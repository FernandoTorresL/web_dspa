@if(count($list_sol))
    <div class="table">
        <table class="table">
            <tr class="small">
                <th scope="col">@sortablelink('created_at', 'Fecha captura')</th>
                <th scope="col">@sortablelink('primer_apellido', 'Primer Apellido')</th>
                <th scope="col">@sortablelink('segundo_apellido', 'Segundo Apellido')</th>
                <th scope="col">@sortablelink('nombre', 'Nombre(s)')</th>
                <th scope="col">@sortablelink('cuenta', 'Usuario')</th>
                <th scope="col">@sortablelink('movimiento_id', 'Movimiento')</th>
                <th scope="col">Grupo</th>
                <th scope="col">Estatus de solicitud</th>
            </tr>
@endif

@forelse($list_sol as $solicitud)

    {{-- Setting the color row by the result of the solicitud --}}
    @if( isset($solicitud->rechazo) || isset($solicitud->resultado_solicitud->rechazo_mainframe) )
            {{-- Solicitud was denny... --}}
            <tr class="small table-danger">
    @else
        @if( !isset($solicitud->resultado_solicitud) )
            {{-- There's not response for the solicitud --}}
            @if( isset($solicitud->lote) )
                {{-- This solicitud has a lote and we're waiting for response --}}
                <tr class="small table-warning">
            @else
                {{-- We're analizing your solicitud --}}
                <tr class="small table-light">
            @endif
        @else
            {{-- There's an OK response for the solicitud --}}
            <tr class="small table-success">
        @endif
    @endif

        <td class="small text-left">{{ $solicitud->created_at }} {{ date('d-M-Y', strtotime($solicitud->created_at )) }}<p>{{ $solicitud->created_at->diffForHumans() }}</p></td>
        <td>{{ $solicitud->primer_apellido }}</td>
        <td>{{ $solicitud->segundo_apellido }}</td>
        <td>{{ $solicitud->nombre }}</td>
        <td>
            <a target="_self" alt="Ver/Editar" href="/ctas/solicitudes/{{ $solicitud->id }}">
                @if( isset($solicitud->resultado_solicitud) )
                    {{--If solicitud has a response ... --}}
                    {{ $solicitud->resultado_solicitud->cuenta }}
                @else
                    {{--  ...show the captured value --}}
                    {{ $solicitud->cuenta }}
                @endif
            </a>
        </td>
        <td class="text-center">{{ $solicitud->movimiento->name }}</td>
        <td>
            @if( $solicitud->movimiento->id == 2 )
                {{--If solicitud is BAJA show the actual group --}}
                {{ $solicitud->gpo_actual->name }}
            @else
                {{-- any other case, show the new group --}}
                {{ $solicitud->gpo_nuevo->name }}
            @endif
        </td>
        {{-- Setting the solicitud status --}}
        @if( isset($solicitud->rechazo) || isset($solicitud->resultado_solicitud->rechazo_mainframe) )
            {{-- Solicitud was denny... --}}
            <td class="small text-right text-danger">No procede<p></p></td>
        @else
            @if( !isset($solicitud->resultado_solicitud) )
                {{-- There's not response for the solicitud --}}
                @if( isset($solicitud->lote) )
                    {{-- This solicitud has a lote and we're waiting for response --}}
                    <td class="small text-right text-warning">Enviada a Mainframe<p>En espera de respuesta</p></td>
                @else
                    {{-- We're analizing your solicitud --}}
                    <td class="small text-right text-primary">En revisión por<p>personal CA-DSPA</p></td>
                @endif

            {{--@elseif( $solicitud->resultado_solicitud->status == 1 )
                --}}{{-- There's an response, but we have to send again the solicitud --}}{{--
                <td class="text-warning">PENDIENTE</td>--}}
            @else
                <td class="small text-right text-success">Atendida<p></p></td>
            @endif
        @endif

        {{--<td class="small">
            <small>
                Capturado por: {{ $solicitud->user->name }}

                {{ $solicitud->created_at->diffForHumans() }}
            </small>
        </td>--}}
    </tr>
@empty
    <p>No hay solicitudes recientes</p>
@endforelse

@if(count($list_sol))
        </table>
    </div>

    <div class="mt-2 mx-auto justify-content-center">
        {!! $list_sol->appends(\Request::except('page'))->render() !!}
    </div>
@endif
