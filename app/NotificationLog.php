<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{	

	/**
     * Setting One-To-Many Relationship with User Model
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

	/**
     * Saving Log 
     *
     * @param array
     * @return boolean
     */
    public static function createLog($params){
    	if(empty($params)){
    		return false;
    	}

    	$Log = new NotificationLog();
		$Log->user_id = $params['user_id'];
		$Log->user_name = $params['user_name'];
		$Log->message = $params['msg'];
        $Log->message_type = $params['action'];

    	if($Log->save()){
    		return true;
    	}
    	
    	return false;
    }
}
