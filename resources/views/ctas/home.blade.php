@extends('layouts.app')

@section('title', 'Ctas')

@section('content')
    <div class="container">
        <div class="row">
            <a class="btn btn-default" href="">Regresar</a>
        </div>
        <div class="row">
            <div class="card-body">
                <h4 class="card-title">Delegación Ciudad de México Norte</h4>
                {{--<p><small class="card-subtitle text-muted">Toledo 21, 8 piso, Colonia Juárez, Delegación Cuauhtemoc, Ciudad de México, 06600</small></p>--}}
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="card-group">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Cuentas en Subdelegaciones</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Polanco: <span class="badge badge-pill badge-primary">156</span></li>
                                <li class="list-group-item">Centro  <span class="badge badge-pill badge-warning">156</span></li>
                                <li class="list-group-item">Santa María La Ribera  <span class="badge badge-pill badge-success">156</span></li>
                                <li class="list-group-item">Sin subdelegación asignada  <span class="badge badge-pill badge-primary">156</span></li>
                            </ul>
                            <br>
                            <h6 class="card-title">Cuentas por tipo</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Genéricas: <span class="badge badge-pill badge-danger">15</span></li>
                            </ul>

                        </div>
                        <div class="card-footer">
                            <small class="text-muted">Situación:</small>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Cuentas por Grupos</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">SSJSAV: <span class="badge badge-pill badge-primary">1</span></li>
                                <li class="list-group-item">SSJDAV: <span class="badge badge-pill badge-danger">4</span></li>
                                <li class="list-group-item">SSJOFA: <span class="badge badge-pill badge-warning">6</span></li>
                                <li class="list-group-item">SSCONS: <span class="badge badge-pill badge-success">253</span></li>
                                <li class="list-group-item">SSADIF: <span class="badge badge-pill badge-warning">234</span></li>
                                <li class="list-group-item">SSOPER: <span class="badge badge-pill badge-primary">42</span></li>
                            </ul>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">Total de cuentas: 654</small>
                        </div>
                    </div>
                    <div class="card">
                        <img class="card-img-top" src="https://picsum.photos/600/338?image=4" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title">Título de la foto Dummy Dummiest</h5>
                            <p class="card-text text-muted">Aquí va un comentario dummy del publicador sobre esta foto</p>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">Last updated 3 mins ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row"></div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">

                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Último inventario de cuentas (Fecha de corte: 24 Julio 2018)</h5>
                        <p class="card-text">Total de Cuentas: 456</p>
                        <a href="inventario" class="btn btn-success">Ir al inventario</a>
                    </div>
                </div>
            </div>
        </div>

@endsection
