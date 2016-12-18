<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopGift extends Model
{
    protected $fillable = [
    	'shop_id',
    	'gift_id',
    	'count'
    ];
}
