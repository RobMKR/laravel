<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{

    /**
     * Overriding Eloquent Save method to get ::afterSave event
     *
     * @param array
     * @return True or False
     */
    public function save(array $options = [])
    {
        // Before Save
        return parent::save();
    }

    /**
     * Setting One-To-Many Relationship with User Model
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Setting One-To-Many Relationship with User Model (column responsible_id)
     */
    public function responsible()
    {
        return $this->belongsTo('App\User', 'responsible_id', 'id');
    }

    /**
     * Setting One-To-Many Relationship with Department Model
     */
    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','description', 'department_id', 'user_id', 'status', 'responsible_id'
    ];

    /**
     * Get array of tickets sorted by status
     *
     * @param object
     * @return array
     */
    public function groupByStatus($tickets){
        $return = [];
        foreach($tickets as $_ticket){
            $return[$_ticket->status][] = $_ticket;
        }
        return $return;
    }
}
