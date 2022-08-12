<div>
    @if(count($active_accounts_list))
        <div>
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Cuenta</th>
                        <th scope="col">Origen</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Grupo</th>
                        <th scope="col">Matricula</th>
                        <th scope="col">Tipo Cta</th>
                    </tr>
                </thead>
                <tbody>
    @endif

    @php
        $var = 0;
    @endphp

    @forelse( $active_accounts_list as $row_active_accounts )
        @php
            $var += 1;
        @endphp
        <tr class="text-monospace">
            <th scope="row">{{ $var }}</th>
            <td class="small">
            @if($row_active_accounts->id == "")
                {{ $row_active_accounts->Cuenta }}
            @else
                <a target="_blank" alt="Ver detalle cta"
                    href="/ctas/solicitudes/search/cta?search_word={{ substr($row_active_accounts->Cuenta, 0, 6) }}">
                        {{ $row_active_accounts->Cuenta }}
                </a>
            @endif
            </td>
            <td class="small">{{ $row_active_accounts->Mov }}</td>
            <td class="small">{{ $row_active_accounts->Nombre }}</td>
            <td class="small">{{ $row_active_accounts->Gpo_unificado }}</td>
            <td class="small">{{ $row_active_accounts->Matricula }}</td>
            <td class="small">{{ $row_active_accounts->Work_area_id == 2 ? 'Cta. Genérica' : "" }}</td>
        </tr>
    @empty
        <p>No hay cuentas registradas en esta delegación</p>
        <br>
        <hr>
    @endforelse

    @if(count($active_accounts_list))
                </tbody>
            </table>
        </div>
    @endif

</div>
