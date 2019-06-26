<div class="col-4">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Opciones administrativas</h5>
        </div>
        <div class="card-body">
            @can('ver_resumen_admin_ctas')
                <a href="/ctas/admin/resumen" target="_blank" class="btn btn-outline-info">
                    Ver Resumen
                </a>
            @endcan

            @can('genera_tabla_oficio')
                <a href="ctas/admin/generatabla" target="_blank" class="btn btn-outline-primary">
                    Generar Tabla
                </a>
            @endcan

            {{--@can('leer_archivo_valijas')
                <a href="ctas/admin/show_create_file_valijas" target="_blank" class="btn btn-outline-secondary">
                    Upload Archivo Valijas
                </a>
            @endcan--}}
        </div>
    </div>
</div>
