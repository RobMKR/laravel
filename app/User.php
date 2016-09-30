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
     * Setting One-To-One Relationship with Departments Model (department admin)
     */
    public function department()
    {
        return $this->hasOne('App\Department', 'owner_id');
    }

    /**
     * Setting One-To-Many Relationship with Departments Model (department staff)
     */
    public function in_department()
    {
        return $this->belongsTo('App\Department', 'id');
    }

    /**
     * Setting One-To-Many Relationship with Book Model
     */
    public function notification_logs()
    {
        return $this->hasMany('App\NotificationLog');
    }

    /**
     * Setting One-To-Many Relationship with Tickets Model where column is owner of ticket
     */
    public function ticketsOwner()
    {
        return $this->hasMany('App\Ticket', 'user_id');
    }

    /**
     * Setting One-To-Many Relationship with Tickets Model where column is responsible of ticket
     */
    public function ticketsResponsible()
    {
        return $this->hasMany('App\Ticket', 'responsible_id');
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
            case 'staff':
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
            'staff' => 'Staff'
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

    /**
     * Getting All Users of department
     *
     * @param int $department_id
     * @return array $users
     */
    public function getDepartmentStaff($department_id){
        $users = $this->where('in_department', $department_id)->pluck('name', 'id');
        return $users;
    }
}
