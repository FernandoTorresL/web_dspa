<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Valija extends Model
{
    protected $guarded = [];

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

    public function hist_valijas()
    {
        return $this->hasMany(Hist_valija::class);
    }

    public function hasBeenModified(Valija $valija) {
        return !$valija->hist_valijas->isEmpty();
    }
}