<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Inventory_cta;

class Group extends Model
{
    protected $guarded = [];
    public $sortable = ['name'];

    public function solicitud()
    {
        return $this->hasMany(Solicitud::class);
    }

    public function inventory_cta()
    {
        return $this->hasOne(Inventory_cta::class, 'id', 'gpo_owner_id');
    }
}
