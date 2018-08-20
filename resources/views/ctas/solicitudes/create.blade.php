@extends('layouts.app')

@section('title', 'Crear Solicitud')

@section('content')
        <div class="row">
            <a class="btn btn-default" href="/">Regresar</a>
        </div>

        </div>
        <form action="/ctas/solicitudes/create" method="POST">
            {{ csrf_field() }}
            <div class="container">
                <div class="row">

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="primer_apellido">Fecha Solicitud</label>
                            <input type="date" name="fecha_solicitud_del" class="form-control @if($errors->has('fecha_solicitud_del')) is-invalid @endif" autofocus>
                            @if ($errors->has('fecha_solicitud_del'))
                                @foreach($errors->get('fecha_solicitud_del') as $error)
                                    <div class="invalid-feedback">{{ $error }}</div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-1">
                    </div>

                    <div class="col-sm-8">
                        <label for="tipo_movimiento">Tipo Movimiento</label>
                        <div class="form-group">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input class="custom-control-input @if($errors->has('tipo_movimiento')) is-invalid @endif" type="radio" name="tipo_movimiento" id="radiotipo_movimiento_Nulo" value="0" hidden checked>
                                @if ($errors->has('tipo_movimiento'))
                                    @foreach($errors->get('tipo_movimiento') as $error)
                                        <div class="invalid-feedback">{{ $error }}</div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input class="custom-control-input" type="radio" name="tipo_movimiento" id="radiotipo_movimiento_alta" value="ALTA">
                                <label class="custom-control-label" for="radiotipo_movimiento_alta">ALTA</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input class="custom-control-input" type="radio" name="tipo_movimiento" id="radiotipo_movimiento_baja" value="BAJA">
                                <label class="custom-control-label" for="radiotipo_movimiento_baja">BAJA</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input class="custom-control-input" type="radio" name="tipo_movimiento" id="radiotipo_movimiento_cambio" value="CAMBIO">
                                <label class="custom-control-label" for="radiotipo_movimiento_cambio">CAMBIO</label>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="subdelegacion">Subdelegación</label>
                            <select class="form-control @if($errors->has('subdelegacion')) is-invalid @endif" id="subdelegacion" name="subdelegacion">
                                <option value="" selected>Seleccione Subdelegación</option>
                                <option value="0">Sin Subdelegación asignada</option>
                                <option value="1">Polanco</option>
                                <option value="2">Centro</option>
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
                            <input type="text" name="primer_apellido" class="form-control @if($errors->has('primer_apellido')) is-invalid @else is-valid @endif">
                            @if ($errors->has('primer_apellido'))
                                @foreach($errors->get('primer_apellido') as $error)
                                    <div class="invalid-feedback">{{ $error }}</div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <label for="segundo_apellido">Segundo Apellido</label>
                        <div class="input-group">
                            <input type="text" name="segundo_apellido" class="form-control @if($errors->has('segundo_apellido')) is-invalid @endif">
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
                        <div class="input-group">
                            <input type="text" name="nombre" class="form-control @if($errors->has('nombre')) is-invalid @endif">
                            @if ($errors->has('nombre'))
                                @foreach($errors->get('nombre') as $error)
                                    <div class="invalid-feedback">{{ $error }}</div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <label for="matricula">Matrícula</label>
                        <div class="input-group">
                            <input type="text" name="matricula" class="form-control @if($errors->has('matricula')) is-invalid @endif" placeholder="# Matrícula / TTD">
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
                        <div class="input-group">
                            <input type="text" name="curp" class="form-control @if($errors->has('curp')) is-invalid @endif">
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
                        <div class="input-group mb-3">
                            <input type="text" name="cuenta" class="form-control @if($errors->has('cuenta')) is-invalid @endif">
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
                            <select class="custom-select @if($errors->has('gpo_actual')) is-invalid @endif" id="inputGroupSelect01" name="gpo_actual">
                                <option value="" selected>Selecciona...</option>
                                <option value="1">SSCONS</option>
                                <option value="2">SSADIF</option>
                                <option value="3">SSOPER</option>
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
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="gpo_nuevo">Grupo Nuevo</label>
                            </div>
                            <select class="custom-select @if($errors->has('gpo_nuevo')) is-invalid @endif" id="inputGroupSelect01" name="gpo_nuevo">
                                <option value="" selected>Selecciona...</option>
                                <option value="1">SSCONS</option>
                                <option value="2">SSADIF</option>
                                <option value="3">SSOPER</option>
                            </select>
                            @if ($errors->has('gpo_nuevo'))
                                @foreach($errors->get('gpo_nuevo') as $error)
                                    <div class="invalid-feedback">{{ $error }}</div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-8">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Comentario</span>
                            </div>
                            <textarea class="form-control" id="comment" name="comment"></textarea>
                        </div>
                    </div>
                </div>

                <div class="input-group text-right">
                    <button type="submit" class="btn btn-primary">Crear Solicitud</button>
                </div>
            </div>
        </form>
@endsection