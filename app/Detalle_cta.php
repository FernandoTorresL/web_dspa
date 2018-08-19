<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalle_cta extends Model
{
    protected $guarded = [];

    public function inventory() {
        return $this->belongsTo(Inventory::class);
    }

    public function gpo_owner() {
        return $this->belongsTo(Group::class, 'gpo_owner_id');
    }

    public function work_area() {
        return $this->belongsTo(Work_area::class);
    }

}
