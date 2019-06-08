<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $guarded = [];

    public function solicitud()
    {
        return $this->hasMany(Solicitud::class);
    }
}
