<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delegacion extends Model
{
    protected $table = 'delegaciones';
    protected $guarded = [];

    public function delegacion()
    {
        return $this->belongsTo(Delegacion::class);
    }
}
