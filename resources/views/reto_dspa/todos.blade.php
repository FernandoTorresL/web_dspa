@extends('layouts.app')

@section('title', '2Reto DSPA')

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
            <h2>Comparativo - Reto DSPA</h2>

            <canvas id="canvas" height="280" width="600"></canvas>

        </div>

    </div>

    <script>
        window.onload = function() {
            var context = document.getElementById('canvas').getContext('2d');

            var canvas = new Chart(context, {
                type: 'line',
                data: {
                    labels: ['07Jul2017', '28Jul2017', '29Ago2017', '08Dic2017', '15Dic2017', '26Abr2019'],
                    datasets: [
                        {
                            label: {!! json_encode($persona_reto_4) !!},
                            fill: false,
                            backgroundColor: 'rgba(41,119,255,1)',
                            borderColor: 'rgba(41,119,255,0.51)',
                            data: [
                                81.80,
                                77.50,
                                77.30,
                                73.65,
                                73.45,
                                75.00
                            ],
                        },
                        {
                            label: {!! json_encode($persona_reto_3) !!},
                            fill: false,
                            backgroundColor: 'rgba(0,255,0,0.3)',
                            borderColor: 'rgba(0,255,0,1)',
                            data: [
                                77.80,
                                76.50,
                                74.70,
                                74.70,
                                75.95,
                                74.05
                            ],
                        },
                        {
                            label: {!! json_encode($persona_reto_1) !!},
                            fill: false,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            data: [
                                55.00,
                                55.20,
                                55.40,
                                52.80,
                                53.20,
                                54.60
                            ],
                        },
                        {
                            label: {!! json_encode($persona_reto_2) !!},
                            fill: false,
                            backgroundColor: 'rgba(255,255,0,0.3)',
                            borderColor: 'rgba(255,255,0,1)',
                            data: [
                                80.40,
                                81.75,
                                81.00,
                                83.50,
                                82.55,
                                88.50
                            ],
                        },
                    ]
                },
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Comparativo'
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Fecha'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Peso'
                            }
                        }]
                    }
                }
            });
        }
    </script>
@endsection
