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

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function assignRole($role) {
        $this->roles()->save(
            Role::whereName($role)->firstOrFail()
        );
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return !! $role->intersect($this->roles)-count();

//        foreach ($role as $r)
//        {
//            if ($this->hasRole($r->name))
//            {
//                return true;
//            }
//        }
//
//        return false;
    }


    public function isAdminUser(User $user) {
        return $this->job->id == Job::find(1)->id || $this->job->id == Job::find(4)->id;
    }

    public function isDSPAUser(User $user) {
        $isDSPAUser =
             $this->delegacion_id == env('DSPA_USER_DEL_1') &&
            (
                $this->job->id == env('DSPA_USER_JOB_1') ||
                $this->job->id == env('DSPA_USER_JOB_2')
            );

        return $isDSPAUser;
    }
}
