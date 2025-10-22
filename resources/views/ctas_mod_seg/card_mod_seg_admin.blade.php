<div class="col-4">
    <div class="card">
        <div class="card-header">
            <h7 class="card-title">Opciones administrativas Ctas_mod_seg Módulo Seguimiento</h7>
        </div>

        <div class="card-body">
            <p class="card-text">
                @can('ver_resumen_admin_ctas_mod_seg_mod_seg')
                    <a href="/ctas_mod_seg/admin/resumen" target="_blank" class="btn btn-outline-info">
                        Resumen general
                    </a>
                @endcan
            </p>
        </div>

    </div>
</div>
