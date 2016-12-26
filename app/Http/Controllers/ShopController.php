<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Http\Requests;
use Session;
use App\Shop;

class ShopController extends Controller
{
	public function index(){
		$shops = Shop::get();
		return view('admin/shops')->with('shops', $shops);
	}

	public function addGet(){
		return view('admin/addShop');
	}

	public function addPost(Request $request){
		$this->validate($request, [
			'short_name' => 'required|unique:shops,short_name',
			'full_name' => 'required'
		]);

		$shop = new Shop();
		$shop->short_name = $request->input('short_name');
		$shop->full_name = $request->input('full_name');
		$shop->save();

		Session::flash('success', 'Shop Created');
		return Redirect::back();
	}	

	public function delete($id){
    	$id = $this->decode($id);
		$shop = Shop::find($id);
    	if(empty($shop)){
    		return Redirect::back()->withErrors(['Shop Not Found']);
    	}
    	if($shop->delete($id)){
    		Session::flash('success', 'Shop Succesfully Deleted');
    		return redirect()->action('ShopController@index');
    	}
    }

    public function edit(Request $request, $id){
    	$id = $this->decode($id);
		$shop = Shop::find($id);

    	if(empty($shop)){
    		return Redirect::back()->withErrors(['Shop Not Found']);
    	}

    	if($request->isMethod('post')){
			$this->validate($request, [
		        'full_name' => 'required|max:255',
		        'short_name' => 'required|max:255|unique:shops,short_name,'.$id,
		    ]);

		    if (!$shop->update($request->all())) {
				return Redirect::back()
		        ->withErrors(['Something wrong happened while saving your model'])
		        ->withInput();
			}

			Session::flash('success', 'Shop Succesfully Updated');
			return redirect()->action('ShopController@index');
		}		


    	return view('admin/editShop')->with('data', $shop);
    }

}
