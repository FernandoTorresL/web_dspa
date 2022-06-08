@extends('layouts.app')

@section('title', 'Editar Solicitud')

@section('content')
    @php

        $estatus_solicitud = $sol_original->status_sol_id;

        switch($estatus_solicitud) {
            case 1:     $color = 'light';       $color_text = 'dark';       $possible_status = [ 2, 3, 4, 5 ]; break;
            case 2:     $color = 'warning';     $color_text = 'warning';    $possible_status = [ 1 ]; break;
            case 3:     $color = 'danger';      $color_text = 'danger';     $possible_status = [ 1, 2 ]; break;
            case 4:     $color = 'secondary';   $color_text = 'secondary';  $possible_status = [ 3, 5 ]; break;
            case 5:     $color = 'primary';     $color_text = 'primary';    $possible_status = [ ]; break;
            case 6:     $color = 'info';        $color_text= 'dark';        $possible_status = [ 7, 8, 9 ]; break;
            case 7:     $color = 'danger';      $color_text = 'danger';     $possible_status = [ 0 ]; break;
            case 8:     $color = 'success';     $color_text = 'success';    $possible_status = [ 0 ]; break;
            case 9:     $color = 'secondary';   $color_text = 'secondary';  $possible_status = [ 3, 7, 8 ]; break;
            default:    $color = 'secondary';
        }
    @endphp

    <div class="row">
        <a class="btn btn-default" href="{{ url('/ctas') }}">Regresar</a>
    </div>
    <br>

    <h5>Edita los datos de la solicitud</h5>

    @can('editar_solicitudes_user_nc')
        <form action="{{ $sol_original->id }}" method="POST" enctype="multipart/form-data">
    @else
        <form action="{{ $sol_original->id }}" method="POST" enctype="multipart/form-data">
    @endcan
        {{ csrf_field() }}

        @if (count($errors) > 0)
            <div class="alert alert-danger">
            <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
            </div>
        @endif

        <div class="container">

            <div>
                <strong>Estado actual:</strong>
                <span class="badge badge-pill badge-{{$color_text}}">
                    {{ isset($sol_original->status_sol) ? $sol_original->status_sol->name : 'Indefinido' }}
                </span>
                <span class="text-{{$color_text}}">
                    {{--{{ isset($sol_original->rechazo) ? $sol_original->rechazo->full_name : '' }}--}}
                    {{ isset($sol_original->rechazo) ? $sol_original->rechazo->full_name : (isset($sol_original->resultado_solicitud) ? '/ '.(isset($sol_original->resultado_solicitud->rechazo_mainframe) ? $sol_original->resultado_solicitud->rechazo_mainframe->name : '' ) : '') }}
                </span>
            </div>

            <br>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <p>Archivo PDF actual:
                            @if (isset($sol_original->archivo))
                                <a href="{{ Storage::disk('public')->url($sol_original->archivo) }}" target="_new">PDF Solicitud</a>
                            @else

                            @endif
                        </p>
                        <div class="input-group">
                            <input type="file" name="archivo" value="{{ $sol_original->archivo }}" class="form-control-file is-valid">

                        </div>
                    </div>
                </div>
            </div>

            @can('editar_solicitudes_user_nc')
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="valija">Valija/Oficio</label>
                            <select class="form-control" id="valija" name="valija">
                                <option value="0" selected>Selecciona...</option>
                                @forelse($valijas as $valija)
                                    @if(isset($sol_original->valija))
                                        @php
                                            $valija->id == old('valija', $sol_original->valija->id) ? $str_check = 'selected' : $str_check = '';
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
                        <input type="date" name="fecha_solicitud" class="form-control @if($errors->has('fecha_solicitud')) is-invalid @endif" value="{{ old('fecha_solicitud', $sol_original->fecha_solicitud_del) }}">
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
                            @php
                                $movimiento->id == old('tipo_movimiento', $sol_original->movimiento->id) ? $str_check = 'checked' : $str_check = '';
                            @endphp
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
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="subdelegacion">Subdelegación</label>
                        <select class="form-control @if($errors->has('subdelegacion')) is-invalid @endif" id="subdelegacion" name="subdelegacion">
                            <option value="" selected>Selecciona...</option>
                            @forelse($subdelegaciones as $sub)
                                @php
                                    $sub->id == old('subdelegacion', $sol_original->subdelegacion->id) ? $str_check = 'selected' : $str_check = '';
                                @endphp
                                <option value="{{ $sub->id }}" {{ $str_check }}>{{ $sub->delegacion->id }} {{ $sub->delegacion->name }} - {{ $sub->num_sub }} {{ $sub->name }}</option>
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
                        <input type="text" name="primer_apellido" class="form-control @if($errors->has('primer_apellido')) @endif" value="{{ strtoupper(old('primer_apellido', $sol_original->primer_apellido)) }}">
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
                        <input type="text" name="segundo_apellido" class="form-control @if($errors->has('segundo_apellido')) is-invalid @endif" value="{{ strtoupper(old('segundo_apellido', $sol_original->segundo_apellido)) }}">
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
                        <input type="text" name="nombre" class="form-control @if($errors->has('nombre')) is-invalid @endif" value="{{ strtoupper(old('nombre', $sol_original->nombre)) }}">
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
                        <input type="text" name="matricula" class="form-control @if($errors->has('matricula')) is-invalid @endif" placeholder="# Matrícula / TTD" value="{{ strtoupper(old('matricula', $sol_original->matricula)) }}">
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
                        <input type="text" name="curp" class="form-control @if($errors->has('curp')) is-invalid @endif" value="{{ strtoupper(old('curp', $sol_original->curp)) }}">
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
                        <input type="text" name="cuenta" class="form-control @if($errors->has('cuenta')) is-invalid @endif" value="{{ strtoupper(old('cuenta', $sol_original->cuenta)) }}">
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
                            @if (!isset($sol_original->gpo_actual->id))
                                @php
                                    $id_gpo_actual = 0;
                                @endphp
                            @else
                                @php
                                    $id_gpo_actual = $sol_original->gpo_actual->id;
                                @endphp
                            @endif
                            @forelse($gruposActual as $gpo_actual)
                                @php
                                    $gpo_actual->id == old('gpo_actual', $id_gpo_actual) ? $str_check = 'selected' : $str_check = '';
                                @endphp
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
                            @if (!isset($sol_original->gpo_nuevo->id))
                                @php
                                    $id_gpo_nuevo = 0;
                                @endphp
                            @else
                                @php
                                    $id_gpo_nuevo = $sol_original->gpo_nuevo->id;
                                @endphp
                            @endif
                            @forelse($gruposNuevo as $gpo_nuevo)
                                @php
                                    $gpo_nuevo->id == old('gpo_nuevo', $id_gpo_nuevo) ? $str_check = 'selected' : $str_check = '';
                                @endphp
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
                        <textarea class="form-control" id="comment" name="comment" placeholder="(Opcional)" rows="2">{{ old('comment', $sol_original->comment) }}</textarea>
                    </div>
                </div>
            </div>

            @can('editar_solicitudes_user_nc')
                <br>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="rechazo">Causa de Rechazo</label>
                            <select class="form-control @if($errors->has('rechazo')) is-invalid @endif" id="rechazo" name="rechazo">
                                <option value="" selected>0 - Sin rechazo</option>
                                @if (!isset($sol_original->rechazo->id))
                                    @php
                                        $id_rechazo = 0;
                                    @endphp
                                @else
                                    @php
                                        $id_rechazo = $sol_original->rechazo->id;
                                    @endphp
                                @endif
                                @forelse($rechazos as $rechazo)
                                    @php
                                        $rechazo->id == old('rechazo', $id_rechazo) ? $str_check = 'selected' : $str_check = '';
                                    @endphp
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
                            <textarea class="form-control" id="final_remark" name="final_remark" placeholder="(Opcional)" rows="2">{{ old('final_remark', $sol_original->final_remark) }}</textarea>
                        </div>
                    </div>
                </div>
            @endcan

            <div class="input-group text-right">
                <button type="submit" class="btn btn-primary">Enviar cambios</button>
            </div>
        </div>
    </form>
@endsection
