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
     * Setting One-To-Many Relationship with User Model on Owner Column
     */
    public function owner()
    {
        return $this->belongsTo('App\User');
    }
}
