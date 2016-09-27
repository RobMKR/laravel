<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\User as User;
use App\Http\Requests;
use Validator;
use Session;

class AdminController extends Controller
{	

	public function index(){
    	// Getting User Level
    	$user_level = Auth::user()->getLevel();
    	switch($user_level){
    		case 2:
    			$users = User::whereIn('role', ['user','author'])->get();
    			break;
    		case 3:
    			$users = User::whereIn('role', ['user','author','admin'])->get();
    			break;
			default:
				$users = [];
    	}
        return view('admin/home')->with('users', $users);
    }

    public function editUser(Request $request, $id){
    	// Decoding Id
    	$id = $this->decode($id);

    	// Getting User
		$user = User::find($id);

		// Redirect with errors, if incorrect id has passed
    	if(empty($user)){
    		return Redirect::back()->withErrors(['User Not Found']);
    	}

    	// Post Data
    	if($request->isMethod('post')){
    		$this->validate($request, [
	            'name' => 'required|max:255',
	            'email' => 'required|email|max:255|unique:users,email,'.$id,
	            'role' => 'required|in:user,admin,superadmin,author',
	        ]);

	        if (!$user->update(Input::all())) {
        		return Redirect::back()
                ->withErrors(['Something wrong happened while saving your model'])
                ->withInput();
    		}

    		Session::flash('success', 'User Succesfully Updated');
    		return redirect()->action('AdminController@index');
    	}

    	// Creating View Data
    	$viewData['user'] = $user;

    	// Getting Available Roles for User
    	$viewData['roles'] = User::getRoles(Auth::user()->role);

    	return view('admin/edit_user')->with('data', $viewData);
    }

    public function deleteUser($id){
    	// Decoding Id
    	$id = $this->decode($id);

    	// Getting User
		$user = User::find($id);

		// Redirect with errors, if incorrect id has passed
    	if(empty($user) || Auth::user()->getLevel($user->role) > Auth::user()->getLevel()){
    		return Redirect::back()->withErrors(['User Not Found']);
    	}

    	if($user->delete($id)){
    		Session::flash('success', 'User Succesfully Deleted');
    		return redirect()->action('AdminController@index');
    	}
    }

    public function superFeatures(){
    	return view('admin/super_features');
    }
}
