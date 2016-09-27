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
     */
    public static function createLog($params){
    	if(empty($params)){
    		return false;
    	}

    	$Log = new NotificationLog();
		$Log->user_id = $params['user_id'];
		$Log->user_name = $params['user_name'];
		$Log->message = $params['msg'];

    	switch($params['action']){
    		case 'add_book':
    			$Log->message_type = 'add_book';
    			break;
			case 'edit_book':;
    			$Log->message_type = 'edit_book';
				break;
			case 'delete_book':
				$Log->message_type = 'delete_book';
				break;
			default:
				$Log->message_type = 'other';
    	}

    	if($Log->save()){
    		return true;
    	}
    	
    	return false;
    }
}
