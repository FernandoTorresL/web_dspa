@extends('layouts.app')

@section('content')
    <p>
        <a class="btn btn-default" href="{{ url('/') }}">Regresar</a>
    </p>


    <div class="card">
        <div class="card-header">
             <p class="h3">Delegación {{ $del_id }} - {{ $del_name }}</p>
        </div>
        <div class="card-body">
            <h5 class="card-title">Inventario de cuentas Mainframe-SINDO</h5>
            <small class="text-muted">Fecha de corte: {{ date('d-M-Y', strtotime($inventory->cut_off_date)) }}</small>
        </div>
    </div>

    @if(count($listado_detalle_ctas) > 1)
        <table class="table table-condensed" id="table_inventario">
            <tr class="small">
                <th class="text-left">#</th>
                <th class="text-center">Usuario</th>
                <th class="text-left" >Nombre Completo</th>
                <th class="text-center" >Grupo</th>
                <th class="text-right">Info</th>
                <th class="text-center">Tipo Cuenta</th>
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
                <td class="small text-left">{{ $var }}</td>
                <td class="small text-left">{{ $detalle_cta->cuenta }}</td>
                <td class="small text-left">{{ $detalle_cta->name }}</td>
                <td class="small text-center">{{ $detalle_cta->gpo_owner->name }}</td>
                <td class="small text-right">{{ $detalle_cta->install_data }}</td>
                <td class="text-right"><span class="badge badge-info">{{ $detalle_cta->work_area->name }}</span></td>
            </tr>
        @empty
            <p>No hay cuentas registradas</p>
        @endforelse
    </table>

@endsection
