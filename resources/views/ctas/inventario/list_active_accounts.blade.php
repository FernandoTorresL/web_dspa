<div class="container">

    <h5 class="text-primary">Listado de Cuentas Activas</h5>
    <br>

    @if(count($active_accounts))
        <div class="row" align="center">
            <div class="mt-2 mx-auto justify-content-center">
            </div>
        </div>

        <div>
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Cuenta</th>
                        <th scope="col">Movimiento</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Gpo Actual</th>
                        <th scope="col">Gpo Nuevo</th>
                        <th scope="col">Matricula</th>
                        <th scope="col">Fecha Mov</th>
                    </tr>
                </thead>
                <tbody>
    @endif

    @php
        $var = 0;
    @endphp

    @forelse( $active_accounts as $row_active_accounts )

        @php
            $var += 1;
        @endphp

                <tr class="text-monospace">
                    <th scope="row">{{ $var }}</th>
                    <td class="small">{{ $row_active_accounts->Cuenta }}</td>
                    <td class="small">{{ $row_active_accounts->Mov }}</td>
                    <td class="small">{{ $row_active_accounts->Nombre }}</td>
                    <td class="small">{{ $row_active_accounts->Gpo_actual }}</td>
                    <td class="small">{{ $row_active_accounts->Gpo_nuevo }}</td>
                    <td class="small">{{ $row_active_accounts->Matricula }}</td>
                    <td class="small">{{ $row_active_accounts->Fecha_mov }}</td>
                    </td>
                </tr>
    @empty
                <p>No hay cuentas registradas en esta delegación</p>
            <br>
            <hr>
    @endforelse

    @if(count($active_accounts))
                </tbody>
            </table>
        </div>

        <div class="row" align="center">
            <div class="mt-2 mx-auto justify-content-center">
            </div>
        </div>
    @endif

</div>
