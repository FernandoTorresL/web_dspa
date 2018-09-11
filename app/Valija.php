<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Valija extends Model
{
    //

    public function solicitud()
    {
        return $this->hasMany(Solicitud::class);
    }

    public function delegacion()
    {
        return $this->belongsTo(Delegacion::class);
    }

}
