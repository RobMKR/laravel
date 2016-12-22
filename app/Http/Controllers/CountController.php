<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Shop;
use App\Gift;
use App\ShopGift;

class CountController extends Controller
{
    public function giftShops(){
    	$viewData['shops'] = Shop::get();
    	$viewData['gifts'] = Gift::get();

    	$ShopGift = new ShopGift();
    	$counts = $ShopGift
    		->join('shops', 'shops.id', '=', 'shop_gifts.shop_id')
            ->join('gifts', 'gifts.id', '=', 'shop_gifts.gift_id')
            ->orderBy('shops.short_name')
            ->select('shops.id as shop_id', 'gifts.id as gift_id', 'shop_gifts.count')
            ->get();

        foreach($counts->toArray() as $_count){
        	$viewData['counts'][$_count['shop_id']][$_count['gift_id']] = $_count['count'];
        }

    	return view('admin/giftShops')->with('data', $viewData);
    }
}
