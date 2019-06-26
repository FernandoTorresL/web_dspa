<div class="col-4">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Creación de nuevas Solicitudes</h5>
        </div>
        <div class="card-body">
            <p class="card-text">
            @can('capture_val_nc')
                <a href="ctas/valijasNC" target="_blank" class="btn btn-success">Crear Valija</a>
            @endcan

            @canany( ['capture_sol_del', 'capture_sol_nc'])
                <a href="ctas/solicitudes" target="_blank" class="btn btn-success">Crear solicitud</a>
            @endcanany
            </p>
        </div>
    </div>
</div>
