<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Vinkla\Hashids\Facades\Hashids as Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User as User;
use LRedis;
use Mail;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    
	/**
     * decoder
     *
     * @param string
     * @return  integer
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
     * encoder
     *
     * @param string
     * @return integer
     */
	protected function encode($id){
		return Hashids::encode($id);
	}

    /**
     * Getting Superadmin Object
     *
     * @return object
     */
	protected function superadmin(){
        return User::where('role', 'superadmin')->first();
    }

    /**
     * Send Notification To Individual User
     *
     * @param array
     * @return json
     */
    protected function __sendIndividualMessage($params){
        $redis = LRedis::connection();
        $data = ['message' => $params['msg'],'type' => $params['type'], 'to' => $params['to']];
        $redis->publish('message', json_encode($data));
        return $data;
    }

    /**
     * Simple Mailer component
     *
     * Note * We are using Mail::send method, which takes about 3-5 seconds waited for mail response
     * Note * To Decrease duration of mails, use Mail::queue instead of Mail::send
     * Note * Queus not supported in Windows (because you must install "pcntl" ext. for PHP that is not supported on Windows)
     * Note * So, in Local Machine we used Mail::send, don't forget to change it to queue when place app on unix based server
     *
     * @param string
     * @param string
     * @param email
     * @return json
     */
    protected function send($title, $content, $to){
        Mail::send('email.blank', ['title' => $title, 'content' => $content], function ($message) use ($to)
        {
            $message->from('me@gmail.com', 'Christian Nwamba');
            $message->to($to);
        });
        return response()->json(['message' => 'Request completed']);
    }

    /**
     * Return true when ticket owner or super admin is logged in
     *
     * @param ticket
     * @return boolean
     */
    protected function checkOwner($ticket){
        if($ticket->user_id === Auth::user()->id || Auth::user()->getLevel() === 3){
            return true;
        }
        return false;
    }
}
