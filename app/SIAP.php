<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Siap extends Model
{
    protected $table = 'siap';
    protected $guarded = [];

    public function delegacion()
    {
        return $this->belongsTo(Delegacion::class);
    }
}
