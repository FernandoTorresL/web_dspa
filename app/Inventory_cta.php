<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Inventory_cta extends Model
{
    use Sortable;

    protected $guarded = [];

    public $sortable = [    'cuenta', 
                            'name', 
                            'ciz_1',
                            'ciz_2',
                            'ciz_3',
                            'gpo_owner_id', 
                            'install_data', 
                            'delegacion_id', 
                            'work_area_id'];

    public function inventory() {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    public function gpo_owner() {
        return $this->belongsTo(Group::class, 'gpo_owner_id');
    }

    public function delegacion() {
        return $this->belongsTo(Delegacion::class, 'delegation_id');
    }

    public function work_area() {
        return $this->belongsTo(Work_area::class);
    }

    public function grupo()
    {
        return $this->hasOne(Group::class, 'id', 'gpo_owner_id');
    }

    public function tipo_cuenta()
    {
        return $this->hasOne(Work_area::class, 'id', 'work_area_id');
    }

    public function cizsSortable($query, $direction)
    {
        return $query->orderBy('ciz_1', $direction)
                    ->orderBy('ciz_2', $direction)
                    ->orderBy('ciz_3', $direction);
    }
}
