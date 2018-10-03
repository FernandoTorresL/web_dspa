<div class="col-6">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Creación de nuevas Solicitudes</h5>
        </div>
        <div class="card-body">
            @can('capture_sol_del')
                <p class="card-text">Captura y envía solicitudes a Nivel Central</p>
                <a href="ctas/solicitudes" class="btn btn-success">Crear solicitud</a>
            @else
                @can('capture_val_nc')
                    <p class="card-text">
                        <a href="ctas/valijasNC" class="btn btn-success">Crear Valija</a>
                    </p>
                @endcan

                @can('capture_sol_nc')
                    <p class="card-text">
                        <a href="ctas/solicitudesNC" class="btn btn-primary">Crear Solicitud</a>
                    </p>
                @endcan
            @endcan
        </div>
    </div>
</div>
