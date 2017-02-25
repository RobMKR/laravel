<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use File;
use App\Http\Requests;
use Session;
use App\Gift;
use App\Shop;
use App\ShopGift;
use DB;

class GiftController extends Controller
{
    public function index(){
		$gifts = Gift::get();
		return view('admin/gifts')->with('gifts', $gifts);
	}

	public function addGet(){
		return view('admin/addGift');
	}

	public function addPost(Request $request){
		$this->validate($request, [
			'name' => 'required|unique:gifts,name',
			'icon_type' => 'required|in:url,class'
		]);

		$gift = new Gift();

		if($request->hasFile('icon_url') && $request->file('icon_url')->isValid()){			
			$file_name = md5(microtime()) . '.' . $request->file('icon_url')->getClientOriginalExtension();
			$path = public_path("/uploads/icons");
			$request->file('icon_url')->move($path, $file_name);
			$gift->icon_url = "/uploads/icons/" . $file_name;
		}

		$gift->name = $request->input('name');
		$gift->icon_type = $request->input('icon_type');
		$gift->icon_class = $request->input('icon_class');
		$gift->save();

		Session::flash('success', 'Gift Created');
		return Redirect::back();
	}	

	public function delete($id){
    	$id = $this->decode($id);
		$gift = Gift::find($id);
    	if(empty($gift)){
    		return Redirect::back()->withErrors(['Gift Not Found']);
    	}
    	if($gift->delete($id)){
    		Session::flash('success', 'Gift Succesfully Deleted');
    		return redirect()->action('GiftController@index');
    	}
    }

    public function edit(Request $request, $id){
    	$id = $this->decode($id);
		$gift = Gift::find($id);

    	if(empty($gift)){
    		return Redirect::back()->withErrors(['Gift Not Found']);
    	}

    	if($request->isMethod('post')){
			$this->validate($request, [
				'name' => 'required|unique:gifts,name,' . $id,
				'icon_type' => 'required|in:url,class'
		    ]);

			if($request->hasFile('icon_url') && $request->file('icon_url')->isValid()){	
				File::delete(substr($gift->icon_url, 1));	

				$file_name = md5(microtime()) . '.' . $request->file('icon_url')->getClientOriginalExtension();
				$path = public_path("/uploads/icons");
				$request->file('icon_url')->move($path, $file_name);
				$gift->icon_url = "/uploads/icons/" . $file_name;
			}

			$gift->name = $request->input('name');
			$gift->icon_type = $request->input('icon_type');
			$gift->icon_class = $request->input('icon_class');

		    if (!$gift->save()) {
				return Redirect::back()
		        ->withErrors(['Something wrong happened while saving your model'])
		        ->withInput();
			}

			Session::flash('success', 'Gift Succesfully Updated');
			return redirect()->action('GiftController@index');
		}		


    	return view('admin/editGift')->with('data', $gift);
    }

    public function assignGet(){
    	$shops = Shop::pluck('short_name', 'id');
    	$gifts = Gift::pluck('name', 'id');

    	$counts = ShopGift::join('shops', 'shops.id', '=', 'shop_gifts.shop_id')
            ->join('gifts', 'gifts.id', '=', 'shop_gifts.gift_id')
            ->orderBy('shops.short_name')
            ->select('shops.id as shop_id', 'gifts.id as gift_id', 'shop_gifts.count as count')
            ->get();

        $new_counts = [];

        foreach($counts as $_count){
        	$new_counts[$_count->shop_id][$_count->gift_id] = $_count->count;
        }

    	if(strpos(\URL::previous(), 'giftShops') !== false){
    		Session::flash('prev', 'admin/giftShops');
    	}

    	return view('admin/assignGift')->with('data', ['shops' => $shops, 'gifts' => $gifts, 'counts' => $new_counts]);
    }

    public function assignPost(Request $request){
    	$this->validate($request, [
			'shop_id' => 'required|exists:shops,id',
			'gift_id' => 'required|exists:gifts,id',
			'count' =>'required|integer'
	    ]);

    	$ShopGift = ShopGift::where('shop_id', $request->shop_id)->where('gift_id', $request->gift_id)->first();
    	if(empty($ShopGift)){
    		$ShopGift = new ShopGift();
    		$ShopGift->shop_id = $request->shop_id;
    		$ShopGift->gift_id = $request->gift_id;
    		$ShopGift->count = $request->count;
    	}else{
			$ShopGift->count += $request->count;
    	}

    	$ShopGift->save();

    	Session::flash('success', "$request->count gifts assigned to shop");

		if(Session::has('prev')){
			return redirect(Session::get('prev'));
		}

		return redirect()->back();
    }

}
