<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    public static function getWeekId($_date){
    	$week = self::where('start', '<=', $_date)->where('end', '>=', $_date)->first();
    	return empty($week) ? null : $week->id;
    }
}
