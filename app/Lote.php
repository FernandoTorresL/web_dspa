<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Lote extends Model
{
    use Sortable;

    protected $guarded = [];

    public $sortable = ['num_lote'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resultado_lote()
    {
        return $this->hasOne(Resultado_Lote::class);
    }

}
