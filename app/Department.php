<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'owner_id',
    ];

    /**
     * Setting One-To-Many Relationship with User Model on departments.owner_id column
     */
    public function owner()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Setting One-To-Many Relationship with User Model on users.in_department column
     */
    public function staff()
    {
        return $this->hasMany('App\User');
    }
}
