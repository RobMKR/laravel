<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Book as Book;

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
     * Get the Author of Book.
     */
    public function books()
    {
        return $this->hasMany('App\Book');
    }


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
            case 'author': 
                return 1;
            default :
                return 0;
        }
    }

    /**
     * Getting Available Roles for user
     *
     * @param string
     */
    public static function getRoles($role){
        $roles = [
            'user' => 'User',
            'author' => 'Author'
        ];
        switch($role){
            case 'superadmin':
                $roles['superadmin'] = 'Super Admin';
            case 'admin':
                $roles['admin'] = 'Admin';
                break;
        }
        return $roles;
    }
}
