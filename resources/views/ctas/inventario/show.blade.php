@extends('layouts.app')

@section('content')
    <p>
        <a class="btn btn-default" href="">Regresar</a>
    </p>
    <h2>Inventario</h2>

    <br>

    <h5>Total de cuentas: {{ $total_detalle_ctas }}</h5>


        @foreach ($listado_detalle_ctas as $detalle_cta)
            @include('ctas.inventario.detalle_cta')
        @endforeach


        {{ $listado_detalle_ctas->links() }}


@endsection
