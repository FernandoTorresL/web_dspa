<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Kyslik\ColumnSortable\Sortable;

class Detalle_cta extends Model
{
    use Sortable;

    protected $guarded = [];

    public $sortable = ['ciz_id', 'cuenta', 'gpo_owner_id', 'delegacion_id', 'work_area_id', 'name', 'created', 'passdate', 'last_access', 'install_data'];


    public function inventory() {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    public function gpo_owner() {
        return $this->belongsTo(Group::class, 'gpo_owner_id');
    }

    public function work_area() {
        return $this->belongsTo(Work_area::class);
    }

}
