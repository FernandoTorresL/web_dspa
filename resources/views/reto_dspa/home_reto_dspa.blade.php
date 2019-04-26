@extends('layouts.app')

@section('title', 'Reto DSPA')

@section('content')
    <div class="container">
        <div class="row">
            <a class="btn btn-default" href="{{ url('/') }}">Inicio</a>
            <a class="nav-link" href="{{ url()->previous() }}">Regresar</a>
        </div>

        @if(session()->has('message'))
            <div class="alert alert-danger">
                {{ session()->get('message') }}
            </div>
        @endif

    <div>
        <h2>Bienvenido al Reto DSPA - Registro de peso</h2>
        <!-- <img src="reto.gif"> -->
        <p>
            La siguiente información solo es visible para <em>personal
                específico </em>de la DSPA que se muestra en la siguiente tabla.
        </p>


        <h4>Viernes 07 julio 2017</h4>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Participante</th>
                <th scope="col">Posición</th>
                <th scope="col">Altura (cm)</th>
                <th scope="col">Peso (kg)</th>
                <th scope="col">Variación (kg)</th>
                <th scope="col">% Variación</th>
                <th scope="col">IMC</th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td>{{ $persona_reto_1 }}</td>
                <td class="center">-</td>
                <td class="right">153</td>
                <td class="right">55.00</td>
                <td class="right">-</td>
                <td class="right">%-</td>
                <td class="right">23.50</td>
            </tr>
            <tr class="cellcolor">
                <td class="left">{{ $persona_reto_3 }}</td>
                <td class="center">-</td>
                <td class="right">172</td>
                <td class="right">77.80</td>
                <td class="right">-</td>
                <td class="right">%-</td>
                <td class="right">26.30</td>
            </tr>
            <tr>
                <td class="left">{{ $persona_reto_2 }}</td>
                <td class="center">-</td>
                <td class="right">162</td>
                <td class="right">80.40</td>
                <td class="right">-</td>
                <td class="right">%-</td>
                <td class="right">30.64</td>
            </tr>
            <tr class="cellcolor">
                <td class="left">{{ $persona_reto_4 }}</td>
                <td class="center">-</td>
                <td class="right">165</td>
                <td class="right">81.80</td>
                <td class="right">-</td>
                <td class="right">%-</td>
                <td class="right">30.05</td>
            </tr>
            </tbody>
        </table>

        <h4>Viernes 28 julio 2017</h4>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Participante</th>
                <th scope="col">Posición</th>
                <th scope="col">Altura (cm)</th>
                <th scope="col">Peso (kg)</th>
                <th scope="col">Variación (kg)</th>
                <th scope="col">% Variación</th>
                <th scope="col">IMC</th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td class="left">{{ $persona_reto_4 }}</td>
                <td class="center">1</td>
                <td class="right">165</td>
                <td class="right">77.50</td>
                <td class="right">-4.30</td>
                <td class="right">-5.26%</td>
                <td class="right">28.47</td>
            </tr>
            <tr>
                <td class="left">{{ $persona_reto_3 }}</td>
                <td class="center">2</td>
                <td class="right">172</td>
                <td class="right">76.50</td>
                <td class="right">-1.30</td>
                <td class="right">-1.67%</td>
                <td class="right">25.86</td>
            </tr>
            <tr>
                <td class="left">{{ $persona_reto_1 }}</td>
                <td class="center">3</td>
                <td class="right">153</td>
                <td class="right">55.20</td>
                <td class="right">+0.20</td>
                <td class="right">+0.36%</td>
                <td class="right">23.58</td>
            </tr>
            <tr>
                <td class="left">{{ $persona_reto_2 }}</td>
                <td class="center">4</td>
                <td class="right">162</td>
                <td class="right">81.75</td>
                <td class="right">+1.35</td>
                <td class="right">+1.68%</td>
                <td class="right">31.15</td>
            </tr>
            </tbody>
        </table>

        <h4>Lunes 29 agosto 2017</h4>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Participante</th>
                <th scope="col">Posición</th>
                <th scope="col">Altura (cm)</th>
                <th scope="col">Peso (kg)</th>
                <th scope="col">Variación (kg)</th>
                <th scope="col">% Variación</th>
                <th scope="col">IMC</th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td class="left">{{ $persona_reto_3 }}</td>
                <td class="center">1</td>
                <td class="right">172</td>
                <td class="right">74.70</td>
                <td class="right">-1.80</td>
                <td class="right">-2.35%</td>
                <td class="right">25.25</td>
            </tr>
            <tr>
                <td class="left">{{ $persona_reto_2 }}</td>
                <td class="center">2</td>
                <td class="right">162</td>
                <td class="right">81.00</td>
                <td class="right">-0.75</td>
                <td class="right">-0.92%</td>
                <td class="right">30.86</td>
            </tr>
            <tr>
                <td class="left">{{ $persona_reto_4 }}</td>
                <td class="center">3</td>
                <td class="right">165</td>
                <td class="right">77.30</td>
                <td class="right">-0.20</td>
                <td class="right">-0.26%</td>
                <td class="right">28.39</td>
            </tr>
            <tr>
                <td class="left">{{ $persona_reto_1 }}</td>
                <td class="center">4</td>
                <td class="right">153</td>
                <td class="right">55.40</td>
                <td class="right">+0.20</td>
                <td class="right">+0.36%</td>
                <td class="right">23.67</td>
            </tr>
            </tbody>
        </table>

        <h4>Viernes 08 Diciembre 2017</h4>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Participante</th>
                <th scope="col">Posición</th>
                <th scope="col">Altura (cm)</th>
                <th scope="col">Peso (kg)</th>
                <th scope="col">Variación (kg)</th>
                <th scope="col">% Variación</th>
                <th scope="col">IMC</th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td class="left">{{ $persona_reto_4 }}</td>
                <td class="center">1</td>
                <td class="right">165</td>
                <td class="right">73.65</td>
                <td class="right">-3.65</td>
                <td class="right">-4.72%</td>
                <td class="right">27.05</td>
            </tr>
            <tr>
                <td class="left">{{ $persona_reto_1 }}</td>
                <td class="center">2</td>
                <td class="right">153</td>
                <td class="right">52.80</td>
                <td class="right">-2.60</td>
                <td class="right">-4.69</td>
                <td class="right">22.56</td>
            </tr>
            <tr>
                <td class="left">{{ $persona_reto_3 }}</td>
                <td class="center">3</td>
                <td class="right">172</td>
                <td class="right">74.70</td>
                <td class="right">0.00</td>
                <td class="right">0.00%</td>
                <td class="right">25.25</td>
            </tr>
            <tr>
                <td class="left">{{ $persona_reto_2 }}</td>
                <td class="center">4</td>
                <td class="right">162</td>
                <td class="right">83.50</td>
                <td class="right">+2.5</td>
                <td class="right">+3.09%</td>
                <td class="right">31.82</td>
            </tr>
            </tbody>
        </table>

        <h4>Viernes 15 Diciembre 2017</h4>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Participante</th>
                <th scope="col">Posición</th>
                <th scope="col">Altura (cm)</th>
                <th scope="col">Peso (kg)</th>
                <th scope="col">Variación (kg)</th>
                <th scope="col">% Variación</th>
                <th scope="col">IMC</th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td class="left">{{ $persona_reto_2 }}</td>
                <td class="center">1</td>
                <td class="right">162</td>
                <td class="right">82.55</td>
                <td class="right">-0.95</td>
                <td class="right">-1.14%</td>
                <td class="right">31.45</td>
            </tr>
            <tr>
                <td class="left">{{ $persona_reto_4 }}</td>
                <td class="center">2</td>
                <td class="right">165</td>
                <td class="right">73.45</td>
                <td class="right">-0.20</td>
                <td class="right">-0.27%</td>
                <td class="right">26.98</td>
            </tr>
            <tr>
                <td class="left">{{ $persona_reto_3 }}</td>
                <td class="center">3</td>
                <td class="right">172</td>
                <td class="right">75.95</td>
                <td class="right">+1.25</td>
                <td class="right">+1.67%</td>
                <td class="right">25.67</td>
            </tr>
            <tr>
                <td class="left">{{ $persona_reto_1 }}</td>
                <td class="center">2</td>
                <td class="right">153</td>
                <td class="right">53.20</td>
                <td class="right">+0.40</td>
                <td class="right">+0.76</td>
                <td class="right">22.73</td>
            </tr>
            </tbody>
        </table>

        <h4>Viernes 26 Abril 2019</h4>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Participante</th>
                <th scope="col">Posición</th>
                <th scope="col">Altura (cm)</th>
                <th scope="col">Peso (kg)</th>
                <th scope="col">Variación (kg)</th>
                <th scope="col">% Variación</th>
                <th scope="col">IMC</th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td class="left">{{ $persona_reto_3 }}</td>
                <td class="center">1</td>
                <td class="right">172</td>
                <td class="right">74.05</td>
                <td class="right">-1.90</td>
                <td class="right">-2.50%</td>
                <td class="right">25.03</td>
            </tr>
            <tr>
                <td class="left">{{ $persona_reto_4 }}</td>
                <td class="center">2</td>
                <td class="right">165</td>
                <td class="right">75.00</td>
                <td class="right">+1.55</td>
                <td class="right">2.11%</td>
                <td class="right">27.55</td>
            </tr>
            <tr>
                <td class="left">{{ $persona_reto_1 }}</td>
                <td class="center">3</td>
                <td class="right">153</td>
                <td class="right">54.60</td>
                <td class="right">+1.40</td>
                <td class="right">+2.63</td>
                <td class="right">23.32</td>
            </tr>
            <tr>
                <td class="left">{{ $persona_reto_2 }}</td>
                <td class="center">4</td>
                <td class="right">162</td>
                <td class="right">88.50</td>
                <td class="right">+5.95</td>
                <td class="right">+7.21%</td>
                <td class="right">33.72</td>
            </tr>
            </tbody>
        </table>

        <div id="field" data-field-id="{{ $persona_reto_2 }}" ></div>

        <canvas id="canvas" height="280" width="600"></canvas>

        <h4 class="text-danger">Próximo día de pesaje: Lunes 14 de mayo, 2019</h4>
    </div>

    </div>

    <script>
        window.onload = function() {
            var context = document.getElementById('canvas').getContext('2d');

            var canvas = new Chart(context, {
                type: 'bar',
                data: {
                    labels: [{!! json_encode($persona_reto_1) !!}, {!! json_encode($persona_reto_3) !!}, {!! json_encode($persona_reto_2) !!}, {!! json_encode($persona_reto_4) !!}],
                    datasets: [
                        {
                            label: '07 Jul 2017',
                            data: [55.00, 77.80, 80.40, 81.80],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(255, 99, 132, 0.2)',
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 99, 132, 1)',
                            ],
                            borderWidth: 1
                        },

                        {
                            label: '28 Jul 2017',
                            data: [55.20, 76.50, 81.75, 77.50],
                            backgroundColor: [
                                'rgba(0,0,255,0.3)',
                                'rgba(0,0,255,0.3)',
                                'rgba(0,0,255,0.3)',
                                'rgba(0,0,255,0.3)',
                            ],
                            borderColor: [
                                'rgba(0, 0, 255, 1)',
                                'rgba(0, 0, 255, 1)',
                                'rgba(0, 0, 255, 1)',
                                'rgba(0, 0, 255, 1)',
                            ],
                            borderWidth: 1
                        },

                        {
                            label: '29 Ago 2017',
                            data: [55.40, 74.70, 81.00, 77.30],
                            backgroundColor: [
                                'rgba(0,255,0,0.3)',
                                'rgba(0,255,0,0.3)',
                                'rgba(0,255,0,0.3)',
                                'rgba(0,255,0,0.3)',
                            ],
                            borderColor: [
                                'rgba(255, 199, 132, 1)',
                                'rgba(255, 199, 132, 1)',
                                'rgba(255, 199, 132, 1)',
                                'rgba(255, 199, 132, 1)',
                            ],
                            borderWidth: 1
                        },

                        {
                            label: '08 Dic 2017',
                            data: [52.80, 74.70, 83.50, 73.65],
                            backgroundColor: [
                                'rgba(255,255,0,0.3)',
                                'rgba(255,255,0,0.3)',
                                'rgba(255,255,0,0.3)',
                                'rgba(255,255,0,0.3)',
                            ],
                            borderColor: [
                                'rgba(255, 199, 132, 1)',
                                'rgba(255, 199, 132, 1)',
                                'rgba(255, 199, 132, 1)',
                                'rgba(255, 199, 132, 1)',
                            ],
                            borderWidth: 1
                        },

                        {
                            label: '15 Dic 2017',
                            data: [53.20, 75.95, 82.55, 73.45],
                            backgroundColor: [
                                'rgba(41,119,255,0.51)',
                                'rgba(41,119,255,0.51)',
                                'rgba(41,119,255,0.51)',
                                'rgba(41,119,255,0.51)',
                            ],
                            borderColor: [
                                'rgba(41,119,255,1)',
                                'rgba(41,119,255,1)',
                                'rgba(41,119,255,1)',
                                'rgba(41,119,255,1)',
                            ],
                            borderWidth: 1
                        },

                        {
                            label: '26 Abr 2019',
                            data: [54.60, 74.05, 88.5, 75.00],
                            backgroundColor: [
                                'rgba(41,19,5,0.51)',
                                'rgba(41,19,5,0.51)',
                                'rgba(41,19,5,0.51)',
                                'rgba(41,19,5,0.51)',
                            ],
                            borderColor: [
                                'rgba(41,19,5,1)',
                                'rgba(41,19,5,1)',
                                'rgba(41,19,5,1)',
                                'rgba(41,19,5,1)',
                            ],
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            })
        }
    </script>

@endsection
