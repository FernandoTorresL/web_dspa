<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Inventory_cta;

class Work_area extends Model
{
    use Sortable;

    public $sortable = ['name'];

    public function inventory_cta()
    {
        return $this->hasOne(Inventory_cta::class, 'id', 'work_area_id');
    }
}
