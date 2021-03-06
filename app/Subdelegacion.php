<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subdelegacion extends Model
{
    protected $table = 'subdelegaciones';
    protected $guarded = [];

    public function delegacion()
    {
        return $this->belongsTo(Delegacion::class);
    }
}
