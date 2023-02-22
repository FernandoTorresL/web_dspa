<div class="col-4">
    <div class="card">

        <div class="card-header">
            <h7 class="card-title">Captura de solicitudes</h7>
        </div>

        <div class="card-body">
            <p>
                @canany( ['capture_sol_del', 'capture_sol_nc'])
                    <a href="ctas/solicitudes" target="_blank" class="btn btn-success">
                        Crear nueva solicitud
                    </a>
                @endcanany
            </p>

            @can('capture_val_nc')
                <p class="card-text">
                    <a href="ctas/valijasNC" target="_blank" class="btn btn-success">
                        Crear nueva Valija
                    </a>
                </p>
            @endcan
        </div>

    </div>
</div>
