<div class="container">
    <p>
        <a class="btn btn-outline-success small" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
            Ver cuentas nuevas
        </a>
    </p>

    <div class="collapse" id="collapseExample">
        <div class="card card-body">
            <p>Listado de cuentas generadas después del corte. 
                Únicas: 
                <span data-placement="center" title="Cuentas únicas" class="badge-pill badge-success">
                    {{ str_pad($solicitudes->count(), 2, '0', STR_PAD_LEFT) }}
                </span>

                | En los 3 CIZs:
                <span data-placement="center" title="Total de cuentas" class="badge-pill badge-success">
                    {{ str_pad($solicitudes->count() * 3, 2, '0', STR_PAD_LEFT) }}
                </span>
            </p>

        @if(count($solicitudes))
            <div class="table table-hover table-sm">
                <table class="table">
                    <thead class="thead-primary">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Usuario</th>
                            <th scope="col"></th>
                            <th scope="col">Nombre Completo</th>
                            <th scope="col">Grupo</th>
                            <th scope="col">Info</th>
                            <th scope="col">Tipo Cuenta</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
        @endif

        @php
            $var = 0;
        @endphp

        @forelse($solicitudes as $clave_solicitud =>$solicitud)
                @php
                    $var += 1;
                @endphp
                        <tr class="table-success text-monospace">
                            <td class="small"><strong>{{ $var }}</strong></td>
                            <td class="small">{{ $solicitud->resultado_solicitud->cuenta }}</td>
                            <td class="small"></td>
                            <td class="small">{{ $solicitud->primer_apellido }} {{ $solicitud->segundo_apellido }} {{ $solicitud->nombre }}</td>
                            <td class="small">{{ isset($solicitud->gpo_nuevo->name) ? $solicitud->gpo_nuevo->name : '--' }}</td>
                            <td class="small">{{ $solicitud->matricula }}</td>
                            <td class="small"></td>
                            <td class="small"></td>
                        </tr>
        @empty
            <p>No hay cuentas nuevas despúes del corte</p>
        @endforelse

        @if(count($solicitudes))
                    </tbody>
                </table>
        @endif

            </div>
        </div>  
    </div>
</div>