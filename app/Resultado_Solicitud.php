<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resultado_Solicitud extends Model
{
    protected $table = 'resultado_solicitudes';
    protected $guarded = [];

    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function grupo()
    {
        return $this->belongsTo(Group::class, 'grupo_id');
    }

    public function rechazo_mainframe()
    {
        return $this->belongsTo(Rechazo_mainframe::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
