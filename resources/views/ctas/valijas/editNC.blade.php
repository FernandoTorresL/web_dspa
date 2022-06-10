@extends('layouts.app')

@section('title', 'Editar Valija')

@section('content')
    <div class="row">
        <a class="btn btn-default" href="{{ url('/ctas') }}">Regresar</a>
    </div>
    <br>

    <h5>Edita los datos de la Valija/Oficio</h5>

    <form action="{{ $val_original->id }}" method="POST" enctype="multipart/form-data">
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
            <br>
            <div class="row">
                <div class="col-sm-10">
                    <div class="form-group">
                        <p>Archivo Actual:
                            @if (isset($val_original->archivo))
                                <a href="{{ Storage::disk('public')->url($val_original->archivo) }}" target="_new">PDF Valija</a>
                            @else

                            @endif
                        </p>

                        <div class="input-group">
                            <input type="file" name="archivo" class="form-control-file btn-outline-info @if($errors->has('archivo')) is-invalid @else is-valid @endif">
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
                <div class="col-sm-4">
                    <label for="num_oficio_ca">Núm. del Área de Gestión</label>
                    <div class="input-group mb-4">
                        <input type="text" name="num_oficio_ca" class="form-control @if($errors->has('num_oficio_ca')) is-invalid @endif" value="{{ old('num_oficio_ca', $val_original->num_oficio_ca) }}">
                        @if ($errors->has('num_oficio_ca'))
                            @foreach($errors->get('num_oficio_ca') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="col-sm-2"></div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="fecha_recepcion_ca">Fecha recepción en Gestión</label>
                        <input type="date" name="fecha_recepcion_ca" class="form-control @if($errors->has('fecha_recepcion_ca')) is-invalid @endif" value="{{ old('fecha_recepcion_ca', $val_original->fecha_recepcion_ca) }}">
                        @if ($errors->has('fecha_recepcion_ca'))
                            @foreach($errors->get('fecha_recepcion_ca') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <label for="num_oficio_del">Núm. de Oficio</label>
                    <div class="input-group mb-4">
                        <input type="text" name="num_oficio_del" class="form-control @if($errors->has('num_oficio_del')) is-invalid @endif" value="{{ old('num_oficio_del', $val_original->num_oficio_del) }}">
                        @if ($errors->has('num_oficio_del'))
                            @foreach($errors->get('num_oficio_del') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="col-sm-2">
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="fecha_valija_del">Fecha de la Valija/Oficio</label>
                        <input type="date" name="fecha_valija_del" class="form-control @if($errors->has('fecha_valija_del')) is-invalid @endif" value="{{ old('fecha_valija_del', $val_original->fecha_valija_del) }}">
                        @if ($errors->has('fecha_valija_del'))
                            @foreach($errors->get('fecha_valija_del') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="delegacion">Delegación</label>
                        <select class="form-control @if($errors->has('delegacion')) is-invalid @endif" id="delegacion" name="delegacion">
                            <option value="" selected>Selecciona...</option>
                            @forelse($delegaciones as $del)
                                @php
                                    $del->id == old('delegacion', $val_original->delegacion->id) ? $str_check = 'selected' : $str_check = '';
                                @endphp
                                <option value="{{ $del->id }}" {{ $str_check }}>{{ $del->id }} - {{ $del->name }}</option>
                            @empty
                            @endforelse
                        </select>
                        @if ($errors->has('delegacion'))
                            @foreach($errors->get('delegacion') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="comentario"></label>
                        <div class="input-group mb-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Comentario</span>
                            </div>
                            <textarea class="form-control" id="comment" name="comment" placeholder="(Opcional)" rows="1">{{ old('comment', $val_original->comment) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="input-group text-right">
                <button type="submit" class="btn btn-info">Editar Valija</button>
            </div>
        </div>
    </form>
    <br>
    <br>
@endsection
