<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Kyslik\ColumnSortable\Sortable;

class Valija extends Model
{
    use Sortable;

    protected $guarded = [];

    public $sortable = ['num_oficio_del'];

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

    public function solicitud()
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

    public function getArchivoAttribute($archivo)
    {

        if (!$archivo || starts_with($archivo, 'http')) {
            return $archivo;
        }

        return Storage::disk('public')->url($archivo);
    }
}
