@extends('layouts.app')

@section('title', 'Valida XML-SIE')

@section('content')

    <div class="row">
        <a class="btn btn-default" href="{{ url('/home') }}">Regresar</a>
    </div>
    <br>

    @if(session()->has('message'))
        <div class="alert alert-danger">
            {{ session()->get('message') }}
        </div>
    @endif


    <div class="card-header card text-white bg-warning">
        <p class="h5">
            Validador archivos XML-SIE - {{ $primer_renglon }}
        </p>
    </div>

    <br>

    <h5>Selecciona archivo XML</h5>

    <form action="/validate_xml/analyze_xml" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
        <div class="container">
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="archivo">Archivo XML:</label>
                        <input type="file" name="archivo" class="form-control-file @if($errors->has('archivo')) is-invalid @else is-valid @endif">
                        @if ($errors->has('archivo'))
                            @foreach($errors->get('archivo') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <br>

            <div class="input-group text-right">
                <button type="submit" class="btn btn-primary">Validar archivo</button>
            </div>
        </div>
    </form>

    <br>

@endsection

