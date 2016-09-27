<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Vinkla\Hashids\Facades\Hashids as Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use LRedis;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    
	/**
     * return Decoded Id
     *
     * @param string
     */
	protected function decode($id){
		$id = Hashids::decode($id);
    	if(!empty($id)){
    		return $id[0];
    	}else{
    		return 0;
    	}
	}

	/**
     * return Encoded Id
     *
     * @param string
     */
	protected function encode($id){
		return Hashids::encode($id);
	}

    /**
     * return Encoded Id
     *
     * @param int
     */
    protected function checkAuthor($user_id){
        if($user_id === Auth::user()->id){
            return true;
        }
        return false;
    }

    /**
     * Send Notification To All connected users
     *
     * @param array message, user
     */
    protected function __sendMessage($params){
        $redis = LRedis::connection();
        $data = ['message' => $params['msg'], 'user' => $params['usr']];
        $redis->publish('message', json_encode($data));
        return $data;
    }
}
