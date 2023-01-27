<div class="col-3">
    <div class="card">
        <div class="card-header">
            <h7 class="card-title">Captura de solicitudes</h7>
        </div>
        <div class="card-body">
            <p class="card-text">
            @can('capture_val_nc')
                <a href="ctas/valijasNC" target="_blank" class="btn vtn-success">Crear nueva Valija</a>
            @endcan

            @canany( ['capture_sol_del', 'capture_sol_nc'])
                <a href="ctas/solicitudes" target="_blank" class="btn btn-success">Crear nueva solicitud</a>
            @endcanany
            </p>
        </div>
    </div>
</div>
