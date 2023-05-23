@extends('layouts.app')

@section('title', 'Resumen Administrativo')

@section('content')

    @if(Auth::check())

        <div class="btn text-white bg-success">
            <p class="h6">
                Resumen Administrativo
            </p>
        </div>

        <br>
        <br>

        @include('ctas.admin.resume')
    @endif

@endsection

