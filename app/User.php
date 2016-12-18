<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Department as Department;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * Getting User role level, highest level is the super admin
     */
    public function getLevel($role = NULL){
        if(!$role){
            $role = $this->role;
        }
        switch($role){
            case 'superadmin' :
                return 3;
            case 'admin':
                return 2;
            case 'user':
                return 1;
            default :
                return 0;
        }
    }
}
