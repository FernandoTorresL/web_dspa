@extends('layouts.app')

@section('title', 'Crear Solicitud')

@section('content')
    <div class="row">
        <a class="btn btn-default" href="{{ url('/ctas') }}">Regresar</a>
    </div>
    <br>

    <h5>Captura los datos de la solicitud</h5>

    @can('capture_sol_nc')
        <form action="solicitudes/createNC" method="POST" enctype="multipart/form-data">
    @else
        <form action="solicitudes/create" method="POST" enctype="multipart/form-data">
    @endcan
        {{ csrf_field() }}
        <div class="container">
            <br>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="archivo">Archivo PDF actual:</label>
                        <input type="file" name="archivo" class="form-control-file @if($errors->has('archivo')) is-invalid @else is-valid @endif">
                        @if ($errors->has('archivo'))
                            @foreach($errors->get('archivo') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

        @can('capture_sol_nc')
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="valija">Valija/Oficio</label>
                        <select class="form-control @if($errors->has('valija')) is-invalid @endif" id="valija" name="valija">
                            <option value="" selected>Selecciona...</option>
                            @forelse($valijas as $valija)
                                @if ($valija->id == old('valija'))
                                    @php
                                        $str_check = 'selected';
                                    @endphp
                                @else
                                    @php
                                        $str_check = '';
                                    @endphp
                                @endif
                                <option value="{{ $valija->id }}" {{ $str_check }}>{{ $valija->num_oficio_ca }}: {{ $valija->delegacion->id }} - {{ $valija->delegacion->name }}</option>
                            @empty
                            @endforelse
                        </select>
                        @if ($errors->has('valija'))
                            @foreach($errors->get('valija') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        @endcan

            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="fecha_solicitud">Fecha de la Solicitud</label>
                        <input type="date" name="fecha_solicitud" class="form-control @if($errors->has('fecha_solicitud')) is-invalid @endif" autofocus value="{{ old('fecha_solicitud') }}">
                        @if ($errors->has('fecha_solicitud'))
                            @foreach($errors->get('fecha_solicitud') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="col-sm-1">
                </div>

                <div class="col-sm-8">

                    <div class="form-group">

                        <div class="custom-control custom-radio">
                            <label for="tipo_movimiento">Tipo de Movimiento</label>
                            <input class="custom-control-input @if($errors->has('tipo_movimiento')) is-invalid @endif" type="radio" name="tipo_movimiento" id="radiotipo_movimiento_Nulo" hidden checked>
                            @if ($errors->has('tipo_movimiento'))
                                @foreach($errors->get('tipo_movimiento') as $error)
                                    <div class="invalid-feedback">{{ $error }}</div>
                                @endforeach
                            @endif
                        </div>
                        @forelse($movimientos as $movimiento)
                            @if ($movimiento->id == old('tipo_movimiento'))
                                @php
                                    $str_check = 'checked';
                                @endphp
                            @else
                                @php
                                    $str_check = '';
                                @endphp
                            @endif
                            <div class="custom-control custom-radio custom-control-inline">
                                <input class="custom-control-input" type="radio" name="tipo_movimiento" id="radiotipo_movimiento_{{ $movimiento->name }}" value="{{ $movimiento->id }}" {{ $str_check }}>
                                <label class="custom-control-label" for="radiotipo_movimiento_{{ $movimiento->name }}">{{ $movimiento->name }}</label>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>

            </div>

            <div class="row">
                @can('capture_sol_del')
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="delegacion">Delegación</label>
                            <p for="delegacion">{{ $del_id }} - {{ $del_name }}</p>
                        </div>
                    </div>
                @endcan

                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="subdelegacion">Subdelegación</label>
                        <select class="form-control @if($errors->has('subdelegacion')) is-invalid @endif" id="subdelegacion" name="subdelegacion">
                            <option value="" selected>Selecciona...</option>
                            @forelse($subdelegaciones as $sub)
                                @if ($sub->id == old('subdelegacion'))
                                    @php
                                        $str_check = 'selected';
                                    @endphp
                                @else
                                    @php
                                        $str_check = '';
                                    @endphp
                                @endif
                            <option value="{{ $sub->id }}" {{ $str_check }}>
                                {{ isset($sub->delegacion->name) ?
                                    str_pad($sub->delegacion->id, 2, '0', STR_PAD_LEFT) . ' ' . $sub->delegacion->name . ' - ' . str_pad($sub->num_sub, 2, '0', STR_PAD_LEFT) . ' ' . $sub->name :
                                    str_pad($sub->num_sub, 2, '0', STR_PAD_LEFT) . ' - ' . $sub->name }}
                            </option>
                            @empty
                            @endforelse
                        </select>
                        @if ($errors->has('subdelegacion'))
                            @foreach($errors->get('subdelegacion') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <label for="primer_apellido">Primer Apellido</label>
                    <div class="input-group">
                        <input type="text" name="primer_apellido" class="form-control @if($errors->has('primer_apellido')) is-invalid @endif" value="{{ strtoupper(old('primer_apellido')) }}">
                        @if ($errors->has('primer_apellido'))
                            @foreach($errors->get('primer_apellido') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="col-sm-4">
                    <label for="segundo_apellido">Segundo Apellido (opcional)</label>
                    <div class="input-group mb-4">
                        <input type="text" name="segundo_apellido" class="form-control @if($errors->has('segundo_apellido')) is-invalid @endif" value="{{ strtoupper(old('segundo_apellido')) }}">
                        @if ($errors->has('segundo_apellido'))
                            @foreach($errors->get('segundo_apellido') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <label for="nombre">Nombre(s)</label>
                    <div class="input-group mb-4">
                        <input type="text" name="nombre" class="form-control @if($errors->has('nombre')) is-invalid @endif" value="{{ strtoupper(old('nombre')) }}">
                        @if ($errors->has('nombre'))
                            @foreach($errors->get('nombre') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="col-sm-2">
                    <label for="matricula">Matrícula</label>
                    <div class="input-group mb-4">
                        <input type="text" name="matricula" class="form-control @if($errors->has('matricula')) is-invalid @endif" placeholder="# Matrícula / TTD" value="{{ strtoupper(old('matricula')) }}">
                        @if ($errors->has('matricula'))
                            @foreach($errors->get('matricula') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <label for="curp">CURP</label>
                    <div class="input-group mb-4">
                        <input type="text" name="curp" class="form-control @if($errors->has('curp')) is-invalid @endif" value="{{ strtoupper(old('curp')) }}">
                        @if ($errors->has('curp'))
                            @foreach($errors->get('curp') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="col-sm-1">

                </div>

                <div class="col-sm-2">
                    <label for="cuenta">USER-ID</label>
                    <div class="input-group mb-4">
                        <input type="text" name="cuenta" class="form-control @if($errors->has('cuenta')) is-invalid @endif" value="{{ strtoupper(old('cuenta')) }}">
                        @if ($errors->has('cuenta'))
                            @foreach($errors->get('cuenta') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="gpo_actual">Grupo Actual</label>
                        </div>
                        <select class="form-control @if($errors->has('gpo_actual')) is-invalid @endif" id="gpo_actual" name="gpo_actual">
                            <option value="" selected>Selecciona...</option>
                            @forelse($gruposActual as $gpo_actual)
                                @if ($gpo_actual->id == old('gpo_actual'))
                                    @php
                                        $str_check = 'selected';
                                    @endphp
                                @else
                                    @php
                                        $str_check = '';
                                    @endphp
                                @endif
                                <option value="{{ $gpo_actual->id }}" {{ $str_check }}>{{ $gpo_actual->name }}</option>
                            @empty
                            @endforelse
                        </select>
                        @if ($errors->has('gpo_actual'))
                            @foreach($errors->get('gpo_actual') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="col-sm-1">
                </div>

                <div class="col-sm-3">
                    <div class="input-group mb-4">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="gpo_nuevo">Grupo Nuevo</label>
                        </div>
                        <select class="form-control @if($errors->has('gpo_nuevo')) is-invalid @endif" id="gpo_nuevo" name="gpo_nuevo">
                            <option value="" selected>Selecciona...</option>
                            @forelse($gruposNuevo as $gpo_nuevo)
                                @if ($gpo_nuevo->id == old('gpo_nuevo'))
                                    @php
                                        $str_check = 'selected';
                                    @endphp
                                @else
                                    @php
                                        $str_check = '';
                                    @endphp
                                @endif
                                <option value="{{ $gpo_nuevo->id }}" {{ $str_check }}>{{ $gpo_nuevo->name }}</option>
                            @empty
                            @endforelse
                        </select>
                        @if ($errors->has('gpo_nuevo'))
                            @foreach($errors->get('gpo_nuevo') as $error)
                                <div class="invalid-feedback"><strong>{{ $error }}</strong></div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8">
                    <div class="input-group mb-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Comentario</span>
                        </div>
                        <textarea class="form-control" id="comment" name="comment" placeholder="(Opcional)" rows="2">{{ old('comment') }}</textarea>
                    </div>
                </div>
            </div>

            @can('capture_sol_nc')
                <br>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="rechazo">Causa de Rechazo</label>
                            <select class="form-control @if($errors->has('rechazo')) is-invalid @endif" id="rechazo" name="rechazo" width="100%">
                                <option value="" selected>0 - Sin rechazo</option>
                                @forelse($rechazos as $rechazo)
                                    @if ($rechazo->id == old('rechazo'))
                                        @php
                                            $str_check = 'selected';
                                        @endphp
                                    @else
                                        @php
                                            $str_check = '';
                                        @endphp
                                    @endif
                                    <option value="{{ $rechazo->id }}" {{ $str_check }}>{{ $rechazo->id }} - {{ $rechazo->full_name }}</option>
                                @empty
                                @endforelse
                            </select>
                            @if ($errors->has('rechazo'))
                                @foreach($errors->get('rechazo') as $error)
                                    <div class="invalid-feedback">{{ $error }}</div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-8">
                        <div class="input-group mb-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Observaciones sobre rechazo</span>
                            </div>
                            <textarea class="form-control" id="final_remark" name="final_remark" placeholder="(Opcional)" rows="2">{{ old('final_remark') }}</textarea>
                        </div>
                    </div>
                </div>
            @endcan

            <div class="row">
                <br>
            </div>

            <div class="row-sm-3">
                <label for="rol">
                    <strong>CAMPOS ESPECÍFICOS PARA USUARIOS DEL MÓDULO DE SEGUIMIENTO (No utilizar para Usuarios SINDO)</strong>
                </label>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="rol">Rol</label>
                        <select class="form-control" id="rol" name="rol">
                            <option value="3" selected>Ventanilla</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <label for="telefono">Teléfono (10 dígitos)</label>
                    <div class="input-group mb-4">
                        <input type="tel" name="telefono" class="form-control @if($errors->has('telefono')) is-invalid @endif" placeholder="0112345678" value="{{ strtoupper(old('telefono')) }}">
                        @if ($errors->has('telefono'))
                            @foreach($errors->get('telefono') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="col-sm-1">

                </div>

                <div class="col-sm-2">
                    <label for="tel_ext">Extensión</label>
                    <div class="input-group mb-4">
                        <input type="number" name="tel_ext" class="form-control @if($errors->has('tel_ext')) is-invalid @endif" placeholder="12345" value="{{ strtoupper(old('tel_ext')) }}">
                        @if ($errors->has('tel_ext'))
                            @foreach($errors->get('tel_ext') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <label for="email">Correo electrónico IMSS</label>
                    <div class="input-group mb-4">
                        <input type="email" name="email" class="form-control @if($errors->has('email')) is-invalid @endif" placeholder="nombre.apellido@imss.gob.mx" value="{{ strtoupper(old('email')) }}">
                        @if ($errors->has('email'))
                            @foreach($errors->get('email') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="col-sm-6">
                    <label for="nombre_pc">Nombre del equipo/PC</label>
                    <div class="input-group mb-4">
                        <input type="text" name="nombre_pc" class="form-control @if($errors->has('nombre_pc')) is-invalid @endif" placeholder="MTO12345WSTF00.NTE.IMSS.GOB.MX" value="{{ strtoupper(old('nombre_pc')) }}">
                        @if ($errors->has('nombre_pc'))
                            @foreach($errors->get('nombre_pc') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <label for="dir_ip">Dirección IP del equipo</label>
                    <div class="input-group mb-4">
                        <input type="text" name="dir_ip" class="form-control @if($errors->has('dir_ip')) is-invalid @endif" placeholder="255.255.255.255" value="{{ strtoupper(old('dir_ip')) }}">
                        @if ($errors->has('dir_ip'))
                            @foreach($errors->get('dir_ip') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="col-sm-1">

                </div>

                <div class="col-sm-4">
                    <label for="mac_address">Dirección física (MAC address)</label>
                    <div class="input-group mb-4">
                        <input type="text" name="mac_address" class="form-control @if($errors->has('mac_address')) is-invalid @endif" placeholder="F4-38-8E-DB-C4-48" pattern="^([0-9a-fA-F]{2}[:\-]){5}[0-9a-fA-F]{2}$" value="{{ strtoupper(old('mac_address')) }}">
                        @if ($errors->has('mac_address'))
                            @foreach($errors->get('mac_address') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <br>
            </div>


            <div class="input-group text-right">
                <button type="submit" class="btn btn-primary">Crear Solicitud</button>
            </div>
        </div>
    </form>
@endsection
