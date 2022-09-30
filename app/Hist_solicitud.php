<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hist_solicitud extends Model
{
    protected $table = 'hist_solicitudes';
    protected $guarded = [];

    public function valija()
    {
        return $this->belongsTo(Valija::class);
    }

    public function lote()
    {
        return $this->belongsTo(Lote::class);
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

    public function rechazo()
    {
        return $this->belongsTo(Rechazo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status_sol()
    {
        return $this->belongsTo(Status_sol::class);
    }

}
