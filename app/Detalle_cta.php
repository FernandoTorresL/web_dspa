<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;

class Detalle_cta extends Model
{
    use Sortable;

    protected $guarded = [];

    //public $sortable = ['ciz_id', 'cuenta', 'gpo_owner_id', 'delegacion_id', 'work_area_id', 'name', 'created', 'passdate', 'last_access', 'install_data'];
    public $sortable = ['cuenta'];


    public function inventory() {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    public function gpo_owner() {
        return $this->belongsTo(Group::class, 'gpo_owner_id');
    }

    public function work_area() {
        return $this->belongsTo(Work_area::class);
    }

    public static function ciz_activos($cuenta) {

        $inventory_id = env('INVENTORY_ID');

        return DB::select( DB::raw("
                SELECT DISTINCT concat_ws('|',
                      IFNULL( (SELECT DISTINCT A.ciz_id
                               FROM detalle_ctas as A
                               WHERE A.ciz_id = 1 AND A.cuenta = tCta.cuenta), '-'),
                      IFNULL( (SELECT DISTINCT A.ciz_id
                               FROM detalle_ctas as A
                               WHERE A.ciz_id = 2 AND A.cuenta = tCta.cuenta), '-'),
                      IFNULL( (SELECT DISTINCT A.ciz_id
                               FROM detalle_ctas as A
                               WHERE A.ciz_id = 3 AND A.cuenta = tCta.cuenta), '-')
                    ) AS 'CIZ_Activos'
                FROM detalle_ctas AS tCta
                WHERE tCta.inventory_id = '$inventory_id'
                AND tCta.cuenta = '$cuenta'
                " ) );
    }

}
