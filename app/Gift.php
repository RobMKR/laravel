<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    protected $fillable = [
    	'name',
    	'icon_type',
    	'icon_url',
    	'icon_class'
    ];
}
