@extends('layouts.app')

@section('title', 'Genera Tabla')

@section('content')

<script>
    function myFunction(val) {
        alert("The input value has changed. The new value is: " + val);
    }
</script>

    <p>
        <a class="btn btn-default" href="{{ url('/ctas') }}">Regresar</a>
    </p>

    @if(Auth::check())
    <div class="card-header card text-white bg-primary">
        <p class="h4">Tabla para Oficio</p>
        @if( isset( $info_lote ) )
            <p>Lote: {{ $info_lote->num_lote }} id: {{ $info_lote->id }}</p>
        @else
            Sin lote asignado
        @endif
        @if( isset( $solicitud_id ) )
            <p>Solicitudes <= {{ $solicitud_id }}</p>
        @endif
        <br>
    </div>

    <form action="/ctas/admin/generatabla/" >
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="loteID">Lote a visualizar:</label>
                {{-- <select class="form-control" id="lote" name="lote" onchange="myFunction(this.value)"> --}}
                <select class="form-control" id="lote" name="loteid" onchange="this.form.submit()">
                    <option value="" selected>Selecciona un lote...</option>
                    @forelse($lista_de_lotes as $lote)
                        @if ($lote->id == old('lote'))
                            @php
                                $str_check = 'selected';
                            @endphp
                        @else
                            @php
                                $str_check = '';
                            @endphp
                        @endif
                        <option value="{{ $lote->id }}" {{ $str_check }}>{{ $lote->num_lote }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>
    </div>
    </form>

        {{-- @include('ctas.admin.genera_tabla_con_lote')

        @include('ctas.admin.genera_tabla') --}}
        @if( isset( $info_lote ) )
                @include('ctas.admin.genera_tabla_sin_resp_mainframe')
                @include('ctas.admin.genera_tabla_resp_mainframe_ok')
                @include('ctas.admin.genera_tabla_resp_mainframe_error')
        @else
            @include('ctas.admin.genera_tabla_con_preautorizados')
            @include('ctas.admin.genera_tabla_sin_preautorizados')
        @endif

        @include('ctas.admin.genera_tabla_valijas')
    @endif

@endsection
