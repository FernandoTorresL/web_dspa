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
            Validador archivos XML-SIE
        </p>
    </div>

    <br>

    <h1>Upload XML File</h1>
    <form action="{{ route('convert_xml') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="file" name="xml_file" id="xml_file">
        <button type="submit">Upload XML</button>
    </form>

    @isset($csv_file)
        <h2>Converted CSV File</h2>

        {{-- <a href="{{ asset('storage/' . $csv_file) }}">Download CSV</a> --}}
        <a href="{{ Storage::disk()->url($csv_file) }}">Descargar CSV</a>
        {{-- <a href="{{ Storage::disk('public')->url($sol_original->archivo) }}" target="_new">PDF Solicitud</a> --}}
    @endisset

    <br>

@endsection

