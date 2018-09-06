<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'matricula', 'name', 'email', 'password', 'avatar', 'delegacion_id', 'job_id', 'status', 'activation_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function delegacion()
    {
        return $this->belongsTo(Delegacion::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function isAdminUser(User $user) {
        return $this->job->id == Job::find(1)->id;
    }
}
