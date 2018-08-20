@extends('layouts.app')

@section('title', 'Ctas')

@section('content')
    <div class="container">
        <div class="row">
            <a class="btn btn-default" href="/">Regresar</a>
        </div>
        <div class="row">
            <div class="card-body">
                <h4 class="card-title">Delegación {{ $del_id }} - {{ $del_name }}</h4>
                {{--<p><small class="card-subtitle text-muted">Toledo 21, 8 piso, Colonia Juárez, Delegación Cuauhtemoc, Ciudad de México, 06600</small></p>--}}
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="card-group">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Cuentas en Subdelegaciones</h6>

                            <dl class="row">
                                <dt class="col-sm-12"></dt>

                                <dd class="col-sm-10 text-truncate">Genéricas: Genéricas Genéricas Genéricas</dd>
                                <dd class="col-sm-2 text-sm-right"><span class="badge badge-pill badge-danger text-sm-right">15</span></dd>

                                <dd class="col-sm-10 text-truncate">Centro</dd>
                                <dd class="col-sm-2 text-right"><span class="badge badge-pill badge-warning">345</span></dd>

                                <dd class="col-sm-10 text-truncate">Santa María La Ribera </dd>
                                <dd class="col-sm-2"><span class="badge badge-pill badge-primary">150</span></dd>

                                <dd class="col-sm-10 text-truncate">Polanco</dd>
                                <dd class="col-sm-2"><span class="badge badge-pill badge-danger">155</span></dd>

                                <dd class="col-sm-10 text-truncate">Sin subdelegación asignada</dd>
                                <dd class="badge badge-pill col-sm-2 badge-success">5</dd>
                            </dl>

                        </div>

                        <div class="card-footer">
                            <small class="text-muted">Situación:</small>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Cuentas por Grupos</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    SSJSAV: <span class="badge badge-pill badge-primary col-sm-2">1</span>
                                </li>
                                <li class="list-group-item">SSJDAV: <span class="badge badge-pill badge-danger">{{ $total_ctas_SSJSAV }}</span></li>
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
                        <a href="ctas/inventario" class="btn btn-success">Ir al inventario</a>
                    </div>
                </div>
            </div>
        </div>

@endsection
