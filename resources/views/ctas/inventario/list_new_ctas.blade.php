@php
    use Carbon\Carbon;
    setlocale(LC_TIME, 'es-ES');
    \Carbon\Carbon::setUtf8(false);
@endphp

<div class="container">
    {{--<h5 class="text-primary">
        Cuentas generadas después del corte: Únicas: {{ $solicitudes->total() }}
         | En los 3 CIZs: {{ $solicitudes->total() * 3 }}
    </h5>--}}
    {{--<div class="row" align="center">
        <div class="mt-2 mx-auto justify-content-center">
            {!! $solicitudes->appends(\Request::except('page'))->render() !!}
        </div>
    </div>--}}
</div>

<div class="table table-hover table-sm">
    <table class="table">
        <thead class="thead-primary">
            <tr>
                <th class="small align-text-top" scope="col">#</th>
                <th class="small align-text-top" scope="col">@sortablelink('cuenta', 'Usuario')</th>
                <th class="small align-text-top" scope="col">CIZ</th>
                <th class="small align-text-top text-sm-left" scope="col">@sortablelink('nombre', 'Nombre Completo')</th>
                <th class="small align-text-top" scope="col">@sortablelink('grupo2.name', 'Grupo')</th>
                <th class="small align-text-top" scope="col">@sortablelink('matrícula', 'Info')</th>
                <th class="small align-text-top" scope="col">Tipo Cuenta</th>
                <th class="small align-text-top" scope="col">Subdelegación</th>
            </tr>
        </thead>
        <tbody>

        @php
            $var = 0;
        @endphp

        @forelse($solicitudes as $clave_solicitud =>$solicitud)
            {{-- Setting the color row by the result of the solicitud --}}
            @if( isset($solicitud->rechazo) || isset($solicitud->resultado_solicitud->rechazo_mainframe) )
                    {{-- Solicitud was denny... --}}
                    <tr class="table-danger">
                        <td class="small">
                            <strong>
                                {{ $var }}
                            </strong>
                        </td>
                        <td class="small">{{ $solicitud->cuenta }}</td>
                        <td class="small">{{ $i }}</td>
                        <td class="small">{{ $solicitud->primer_apellido }} {{ $solicitud->segundo_apellido }} {{ $solicitud->nombre }}</td>
                        <td class="small">{{ isset($solicitud->grupo2->name) ? $solicitud->grupo2->name : '--' }}</td>
                        <td class="small">{{ $solicitud->matricula }}</td>
                        <td class="small"></td>
                        <td class="small"></td>
            @else
                @if( !isset($solicitud->resultado_solicitud) )
                    {{-- There's not response for the solicitud --}}
                    @if( isset($solicitud->lote) )
                        {{-- This solicitud has a lote and we're waiting for response --}}
                        <tr class="table-warning">
                    @else
                        {{-- We're analizing your solicitud --}}
                        <tr class="table-light">
                    @endif
                @else
                    {{-- There's an OK response for the solicitud --}}
                    @for($i = 1; $i <= 3; $i++)
                        @php
                            $var += 1;
                        @endphp
                    <tr class="table-success">
                        <td class="small">
                            <strong>
                            {{ $var }}
                            </strong>
                        </td>
                        <td class="small">{{ $solicitud->cuenta }}</td>
                        <td class="small">{{ $i }}</td>
                        <td class="small">{{ $solicitud->primer_apellido }} {{ $solicitud->segundo_apellido }} {{ $solicitud->nombre }}</td>
                        <td class="small">{{ isset($solicitud->grupo2->name) ? $solicitud->grupo2->name : '--' }}</td>
                        <td class="small">{{ $solicitud->matricula }}</td>
                        <td class="small"></td>
                        <td class="small"></td>
                    @endfor
                @endif
            @endif
                    </tr>
@empty
    <p>No hay solicitudes que coincidan con el criterio de búsqueda</p>
@endforelse
        </tbody>
    </table>
</div>
