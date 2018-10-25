<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Solicitud extends Model
{
    protected $table = 'solicitudes';
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

    public function hist_solicitudes()
    {
        return $this->hasMany(Hist_solicitud::class);
    }

    public function resultado_solicitud()
    {
        return $this->hasOne(Resultado_Solicitud::class);
    }

    public function hasBeenModified(Solicitud $solicitud) {
        return !$solicitud->hist_solicitudes->isEmpty();
    }

    public function getArchivoAttribute($archivo)
    {

        if (!$archivo || starts_with($archivo, 'http')) {
            return $archivo;
        }

        return Storage::disk('public')->url($archivo);
    }
}
