<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Track_aud extends Model
{
    protected $table = 'tracks_aud';
    protected $guarded = [];

    public function type()
    {
        return $this->belongsTo(Type_aud::class);
    }

    public function action()
    {
        return $this->belongsTo(Action_aud::class);
    }

    public function operation()
    {
        return $this->belongsTo(Operation_aud::class);
    }

    public function table()
    {
        return $this->belongsTo(Table_aud::class);
    }

    public function ip()
    {
        return $this->belongsTo(Ip_aud::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
