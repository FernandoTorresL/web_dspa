@extends('layouts.app')

@section('title', 'Listado Solicitudes')

@section('content')
    <p>
        <a class="btn btn-default" href="{{ url('/') }}">Inicio</a>
        <a class="btn btn-default" href="{{ url()->previous() }}">Regresar</a>
    </p>

    @if(Auth::check())
        <div class="card text-white bg-primary">
            <div class="card-header">
                <p class="h4">Listado solicitudes - Delegación {{ str_pad(Auth::user()->delegacion->id, 2, '0', STR_PAD_LEFT) }} - {{ Auth::user()->delegacion->name }}</p>
            </div>
        </div>
        <br>
        <br>

        @can('ver_buscar_cta')
            <form action="/ctas/solicitudes/search/cta">
                {{ csrf_field() }}
                <div class="col-sm-3">
                    <div class="input-group">
                        <input onClick="this.setSelectionRange(0, this.value.length)"
                               type="text" id="search_word" name="search_word"
                               class="form-control @if($errors->has('search_word')) is-invalid @endif"
                               value="{{ strtoupper($search_word) }}">
                        @if ($errors->has('search_word'))
                            @foreach($errors->get('search_word') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                        <span class="input-group-append">
                            <button class="btn btn-outline-success">Buscar</button>
                        </span>
                    </div>
                </div>
            </form>
            <br>
            <br>
        @endcan

        @include('ctas.solicitudes.list')

    @endif

@endsection
