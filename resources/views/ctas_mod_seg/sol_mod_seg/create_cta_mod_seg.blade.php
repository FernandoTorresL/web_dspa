@extends('layouts.app')

@section('title', 'Crear Solicitud del Módulo de Seguimiento')

@section('content')
    <div class="row">
        <a class="btn btn-default" href="{{ url('/ctas_mod_seg') }}">Regresar</a>
    </div>
    <br>

    <h5>Captura solicitud de usuario - Módulo de Seguimiento de Solicitudes Patronales</h5>

    @can('capture_sol_mod_seg_nc')
        <form action="sol_mod_seg/create_cta_mod_segNC" method="POST" enctype="multipart/form-data">
    @else
        <form action="sol_mod_seg/create_cta_mod_seg" method="POST" enctype="multipart/form-data">
    @endcan
        {{ csrf_field() }}
        <div class="container">

            <br>
            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="archivo_ine">INE:</label>
                        <input type="file" name="archivo_ine" class="form-control-file @if($errors->has('archivo_ine')) is-invalid @else is-valid @endif">
                        @if ($errors->has('archivo_ine'))
                            @foreach($errors->get('archivo_ine') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="archivo_comp_dom">Comprobante domicilio:</label>
                        <input type="file" name="archivo_comp_dom" class="form-control-file @if($errors->has('archivo_comp_dom')) is-invalid @else is-valid @endif">
                        @if ($errors->has('archivo_comp_dom'))
                            @foreach($errors->get('archivo_comp_dom') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="archivo_responsiva">Responsiva:</label>
                        <input type="file" name="archivo_responsiva" class="form-control-file @if($errors->has('archivo_responsiva')) is-invalid @else is-valid @endif">
                        @if ($errors->has('archivo_responsiva'))
                            @foreach($errors->get('archivo_responsiva') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <br>
            <br>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="fecha_sol_mod_seg">Fecha de Solicitud</label>
                        <input type="date" name="fecha_sol_mod_seg" class="form-control @if($errors->has('fecha_sol_mod_seg')) is-invalid @endif" autofocus value="{{ old('fecha_sol_mod_seg') }}">
                        @if ($errors->has('fecha_sol_mod_seg'))
                            @foreach($errors->get('fecha_sol_mod_seg') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="col-sm-1">
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="rol">Rol</label>
                        <select class="form-control @if($errors->has('rol')) is-invalid @endif" id="rol" name="rol">
                            <option value="" selected>Selecciona...</option>
                            @forelse($roles as $rol)
                                @if ($rol->id == old('rol'))
                                    @php
                                        $str_check = 'selected';
                                    @endphp
                                @else
                                    @php
                                        $str_check = '';
                                    @endphp
                                @endif
                                <option value="{{ $rol->id }}" {{ $str_check }}>{{ $rol->num_oficio_ca }}: {{ $rol->delegacion->id }} - {{ $rol->delegacion->name }}</option>
                            @empty
                            @endforelse
                        </select>
                        @if ($errors->has('rol'))
                            @foreach($errors->get('rol') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                @can('capture_sol_mod_seg_del')
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

                <div class="col-sm-4">
                    <label for="matricula">Matrícula empleado IMSS</label>
                    <div class="input-group mb-4">
                        <input type="text" name="matricula" class="form-control @if($errors->has('matricula')) is-invalid @endif" placeholder="987654321 / TTD / SIN DATO" value="{{ strtoupper(old('matricula')) }}">
                        @if ($errors->has('matricula'))
                            @foreach($errors->get('matricula') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <label for="curp">CURP</label>
                    <div class="input-group mb-4">
                        <input type="text" name="curp" class="form-control @if($errors->has('curp')) is-invalid @endif" placeholder="ABCD800102MASRGR01" value="{{ strtoupper(old('curp')) }}">
                        @if ($errors->has('curp'))
                            @foreach($errors->get('curp') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="col-sm-3">
                    <label for="usuario_mod_seg">USUARIO</label>
                    <div class="input-group mb-4">
                        <input type="text" name="usuario_mod_seg" class="form-control @if($errors->has('usuario_mod_seg')) is-invalid @endif" placeholder="ABCD-3916" value="{{ strtoupper(old('usuario_mod_seg')) }}">
                        @if ($errors->has('usuario_mod_seg'))
                            @foreach($errors->get('usuario_mod_seg') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            
            <div class="row">
                <div class="col-sm-3">
                    <label for="telefono">Teléfono (10 dígitos)</label>
                    <div class="input-group mb-4">
                        <input type="tel" name="telefono" class="form-control @if($errors->has('telefono')) is-invalid @endif" placeholder="0112345678" pattern="[0-9]{2}[0-9]{4}[0-9]{4}" value="{{ strtoupper(old('telefono')) }}">
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
                <div class="col-sm-8">
                    <div class="input-group mb-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Comentario</span>
                        </div>
                        <textarea class="form-control" id="comment" name="comment" placeholder="(Opcional)" rows="2">{{ old('comment') }}</textarea>
                    </div>
                </div>
            </div>

            @can('capture_sol_mod_seg_nc')
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

            <div class="input-group text-right">
                <button type="submit" class="btn btn-primary">Crear Solicitud</button>
            </div>
        </div>
    </form>
@endsection
