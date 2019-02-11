<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resultado_Lote extends Model
{
    protected $table = 'resultado_lotes';
    protected $guarded = [];

    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }

}
