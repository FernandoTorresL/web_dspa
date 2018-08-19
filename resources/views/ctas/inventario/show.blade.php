@extends('layouts.app')

@section('content')
    <p>
        <a class="btn btn-default" href="/ctas">Regresar</a>
    </p>
    <h2>Inventario Delegación {{ $del_id }} - {{ $del_name }}</h2>
    <br>
    <h5>Total de cuentas: {{ $total_detalle_ctas }}</h5>
    <br>
    <br>
    @if(count($listado_detalle_ctas) > 1)
        <table class="table table-condensed" id="table_inventario">
            <tr>
                <th class="text-left">#</th>
                <th class="text-center">Usuario</th>
                <th class="text-center" >Nombre Completo</th>
                <th class="text-center" >Grupo</th>
                <th class="text-right">Info</th>
                <th class="text-center" width="100%">Tipo Cuenta</th>
            </tr>
    @endif

        @php
            $var = 0;
        @endphp

        @forelse($listado_detalle_ctas as $detalle_cta)
            @php
                $var = $var + 1;
            @endphp
            <tr>
                <td class="text-left">{{ $var }}</td>
                <td class="text-left">{{ $detalle_cta->cuenta }}</td>
                <td class="text-left" width="30%">{{ $detalle_cta->name }}</td>
                <td class="text-center">{{ $detalle_cta->gpo_owner_id }}</td>
                <td class="text-right" width="40%">{{ $detalle_cta->install_data }}</td>
                <td class="text-right" width="40%">{{ $detalle_cta->work_area_id }}</td>
            </tr>
        @empty
            <p>No hay cuentas registradas</p>
        @endforelse
    </table>

@endsection
