@extends('layouts.app')

@section('title', 'Leer archivo de Valijas Nivel Central')

@section('content')
    <div class="row">
        <a class="btn btn-default" href="{{ url('/ctas') }}">Regresar</a>
    </div>
    <br>

    <div class="row">
        <div class="card-body">
            {{--<h4 class="card-title">{{ $primer_renglon }}</h4>--}}
        </div>
    </div>

    <h5>Selecciona el archivo con los datos de las valijas</h5>

    <form action="/ctas/admin/create_file_valijas" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="container">
            <br>
            <div class="row">
                <div class="col-sm-10">
                    <div class="form-group">
                        <label for="archivo">Archivo</label>
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
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="comentario"></label>
                        <div class="input-group mb-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Comentario</span>
                            </div>
                            <textarea class="form-control" id="comment" name="comment" placeholder="(Opcional)" rows="1">{{ old('comment') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="input-group text-right">
                <button type="submit" class="btn btn-primary">Leer Archivo Valijas</button>
            </div>
        </div>
    </form>
    <br>
    <br>
@endsection
