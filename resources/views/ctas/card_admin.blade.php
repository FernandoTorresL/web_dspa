<div class="col-4">
    <div class="card">
        <div class="card-header">
            <h7 class="card-title">Opciones administrativas</h7>
        </div>

        <div class="card-body">
            <p class="card-text">
                @can('ver_resumen_admin_ctas')
                    <a href="/ctas/admin/resumen" target="_blank" class="btn btn-outline-info">
                        Resumen general
                    </a>
                @endcan
            </p>

            <p>
                @can('genera_tabla_oficio')
                    <a href="ctas/admin/generatabla/" target="_blank" class="btn btn-outline-primary">
                        Solicitudes sin lote
                    </a>
                @endcan
            </p>

            <p>
                @can('crear_lote')
                    <a href="ctas/admin/captura_lote" target="_blank" class="btn btn-outline-secondary">
                        Capturar nuevo lote
                    </a>
                @endcan
            </p>

            {{--@can('leer_archivo_valijas')
                <a href="ctas/admin/show_create_file_valijas" target="_blank" class="btn btn-outline-secondary">
                    Upload Archivo Valijas
                </a>
            @endcan--}}
        </div>

    </div>
</div>
