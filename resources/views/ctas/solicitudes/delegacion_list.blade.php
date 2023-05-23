@extends('layouts.app')

@section('title', 'Listado Solicitudes')

@section('content')

    @if(Auth::check())
        <div class="btn text-white bg-primary">
            <p class="h6">Solicitudes -
                @if ( Auth::user()->delegacion_id <> 9 )
                    {{ env('OOAD') }} - 
                    {{ Auth::user()->delegacion->name }}
                @else
                    Todas las OOAD's
                @endif
            </p>
        </div>
        <br>
        <br>

        @can('ver_buscar_cta')
            <form action="/ctas/solicitudes/search/cta">
                {{ csrf_field() }}
                <div class="col-sm-4">
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
                            <button class="btn btn-sm btn-outline-success">Buscar</button>
                        </span>
                    </div>
                </div>
            </form>
            <br>
        @endcan

        @include('ctas.solicitudes.list')

    @endif

@endsection
