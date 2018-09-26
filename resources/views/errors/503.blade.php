<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'En mantenimiento') - Portal DSPA </title>

    <!-- Scripts -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ url('../vendor/twbs/bootstrap/dist/css/bootstrap.css') }}"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

</head>

{{--@section('title', 'En mantenimiento')--}}

    <style>
        body { text-align: center; padding: 150px; }
        h1 { font-size: 50px; }
        body { font: 20px Helvetica, sans-serif; color: #333; }
        article { display: block; text-align: left; width: 650px; margin: 0 auto; }
    </style>

    <article>
        <h1>Sitio en mantenimiento</h1>
        <br>
        <br>

        <h3>¡Regresaremos pronto!</h3>
        <div>
            <p>Pedimos disculpas por los inconvenientes ocasionados pero estamos trabajando en cosas interesantes. ¡Pronto estaremos en línea de nuevo!</p>
            <p>&mdash; El equipo DSPA</p>
        </div>
    </article>
