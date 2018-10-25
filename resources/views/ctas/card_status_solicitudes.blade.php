<div class="col-10 col-md-12">
    <br>
</div>

<div class="col-6">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Estatus de las Solicitudes</h5>
        </div>
        <div class="card-body">
            @can('ver_status_solicitudes')
                <p class="card-text">Consulta el listado de Solicitudes</p>
                <a href="ctas/status/solicitudes" class="btn btn-warning">Consultar estatus</a>
            @endcan
        </div>
    </div>
</div>
