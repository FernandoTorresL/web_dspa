@extends('layouts.app')

@section('title', 'Crear Solicitud del Módulo de Seguimiento')

@section('content')
    <div class="row">
        <a class="btn btn-default" href="{{ url('/ctas_mod_seg') }}">Regresar</a>
    </div>
    <br>

    <h5>Captura solicitud de usuario - Módulo de Seguimiento de Solicitudes Patronales</h5>

    @can('capture_sol_mod_seg_nc')
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
                        <label for="archivo_INE">INE:</label>
                        <input type="file" name="archivo_INE" class="form-control-file @if($errors->has('archivo_INE')) is-invalid @else is-valid @endif">
                        @if ($errors->has('archivo_INE'))
                            @foreach($errors->get('archivo_INE') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
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
                <div class="col-sm-4">
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

                <div class="col-sm-6">
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
                    <label for="cuenta">USUARIO</label>
                    <div class="input-group mb-4">
                        <input type="text" name="usuario_mod_seg" class="form-control @if($errors->has('usuario_mod_seg')) is-invalid @endif" value="{{ strtoupper(old('usuario_mod_seg')) }}">
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
                    <label for="correo">Correo electrónico IMSS</label>
                    <div class="input-group mb-4">
                        <input type="email" name="correo" class="form-control @if($errors->has('correo')) is-invalid @endif" value="{{ strtoupper(old('correo')) }}">
                        @if ($errors->has('correo'))
                            @foreach($errors->get('correo') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="col-sm-1">

                </div>

<!--                 <div class="col-sm-2">
                    <label for="usuario_mod_seg">USUARIO</label>
                    <div class="input-group mb-4">
                        <input type="text" name="usuario_mod_seg" class="form-control @if($errors->has('usuario_mod_seg')) is-invalid @endif" value="{{ strtoupper(old('usuario_mod_seg')) }}">
                        @if ($errors->has('usuario_mod_seg'))
                            @foreach($errors->get('usuario_mod_seg') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div> -->
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
