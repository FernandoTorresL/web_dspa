<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hist_valija extends Model
{
    protected $guarded = [];

    public function valija_original()
    {
        return $this->belongsTo(Valija::class);
    }

    public function delegacion()
    {
        return $this->belongsTo(Delegacion::class);
    }

    public function rechazo()
    {
        return $this->belongsTo(Rechazo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class);
    }
}
