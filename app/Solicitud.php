<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $table = 'solicitudes';
    protected $guarded = [];

    public function valija()
    {
        return $this->belongsTo(Valija::class);
    }

    public function delegacion()
    {
        return $this->belongsTo(Delegacion::class);
    }

    public function subdelegacion()
    {
        return $this->belongsTo(Subdelegacion::class);
    }

    public function movimiento()
    {
        return $this->belongsTo(Movimiento::class);
    }

    public function gpo_actual()
    {
        return $this->belongsTo(Group::class, 'gpo_actual_id');
    }

    public function gpo_nuevo()
    {
        return $this->belongsTo(Group::class, 'gpo_nuevo_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
