<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
    	'phone',
    	'name',
    	'surname',
    	'passport_screen'
    ];

    public static function getByPhone($phone){
    	$client = self::where('phone', $phone)->first();
    	return empty($client) ? null : $client;
    }
}
