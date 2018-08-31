@extends('layouts.app')

@section('title', 'Crear Solicitud')

@section('content')
    <div class="row">
        <a class="btn btn-default" href="{{ url('/') }}">Regresar</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    </div>
    <form action="solicitudes/create" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="container">

            <div class="row">
                <div class="col-sm-10">
                    <div class="form-group">
                        <label for="archivo">PDF de la Solicitud</label>
                        <div class="input-group">
                            <input type="file" name="archivo" class="form-control-file @if($errors->has('archivo')) is-invalid @else is-valid @endif">
                            @if ($errors->has('archivo'))
                                @foreach($errors->get('archivo') as $error)
                                    <div class="invalid-feedback">{{ $error }}</div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

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
                    <label for="tipo_movimiento">Tipo de Movimiento</label>
                    <div class="form-group">
                        <div class="custom-control custom-radio">
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
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="subdelegacion">Subdelegación</label>
                        <select class="form-control @if($errors->has('subdelegacion')) is-invalid @endif" id="subdelegacion" name="subdelegacion">
                            {{--<option value="" selected>Selecciona...</option>--}}
                            @forelse($subdelegaciones as $subdelegacion)
                                @if ($subdelegacion->id == old('subdelegacion'))
                                    @php
                                        $str_check = 'selected';
                                    @endphp
                                @else
                                    @php
                                        $str_check = '';
                                    @endphp
                                @endif
                                <option value="{{ $subdelegacion->id }}" {{ $str_check }}>{{ $subdelegacion->num_sub }} - {{ $subdelegacion->name }}</option>
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
                        <input type="text" name="primer_apellido" class="form-control @if($errors->has('primer_apellido')) is-invalid @else is-valid @endif" value="{{ strtoupper(old('primer_apellido')) }}">
                        @if ($errors->has('primer_apellido'))
                            @foreach($errors->get('primer_apellido') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="col-sm-4">
                    <label for="segundo_apellido">Segundo Apellido</label>
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
                        <textarea class="form-control" id="comment" name="comment">{{ old('comment') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="input-group text-right">
                <button type="submit" class="btn btn-primary">Crear Solicitud</button>
            </div>
        </div>
    </form>
@endsection
