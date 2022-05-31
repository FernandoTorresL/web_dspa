@extends('layouts.app')

@section('title', 'Capturar Nuevo Lote Nivel Central')

@section('content')
    <div class="row">
        <a class="btn btn-default" href="{{ url('/ctas') }}">Regresar</a>
    </div>
    <br>

    <div class="card text-white bg-primary">
        <div class="card-header">
            <p class="h4">Últimos lotes creados</p>
        </div>
    </div>

    @include('ctas.admin.resume_lotes')

    <div class="row">
        <div class="card-body">
            <h4 class="card-title"> Crear Lote</h4>
        </div>
    </div>

    <h5>Captura los datos del nuevo lote</h5>

    <form action="crear_lote" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="container">
            <br>
            <div class="row">
                <div class="col-sm-4">
                    <label for="num_lote">Núm. de Lote (D999/YYYY)</label>
                    <div class="input-group mb-8">
                        <input type="text" name="num_lote" class="form-control @if($errors->has('num_lote')) is-invalid @endif" value="{{ old('num_lote') }}">
                        @if ($errors->has('num_lote'))
                            @foreach($errors->get('num_lote') as $error)
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
                            <textarea class="form-control" id="comment" name="comment" placeholder="(Opcional)" rows="1">{{ old('comment') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="input-group text-right">
                <button type="submit" class="btn btn-primary">Crear Lote</button>
            </div>
        </div>
    </form>
    <br>
    <br>
@endsection
