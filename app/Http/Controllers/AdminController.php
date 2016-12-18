<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\User as User;
use App\Department as Department;
use App\Ticket as Ticket;
use App\NotificationLog as Log;
use App\Http\Requests;
use Validator;
use Session;

class AdminController extends Controller
{
    /**
     * Admin Panel Home Page
     *
     * @return view
     */
	public function index(){
    	// Getting User Level
    	$user_level = Auth::user()->getLevel();
    	switch($user_level){
    		case 2:
    			$users = User::whereIn('role', ['user','staff'])->get();
    			break;
    		case 3:
    			$users = User::where('role', '!=', 'superadmin')->get();
    			break;
			default:
				$users = [];
    	}
        return view('admin/home')->with('users', $users);
    }

    /**
     * Admin Panel Edit Account
     *
     * @return view
     */
    public function editAccount(Request $request){
        // Getting User
        $user = Auth::user();

        // Post Data
        if($request->isMethod('post')){
            // Validating other fields
            $this->validate($request, [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users,email,'.$user->id,
                'password' => 'min:6|old_password:' . Auth::user()->password,
                'new_password' => 'confirmed|min:6',
                'new_password_confirmation' => 'min:6'
            ]);
            $update['name'] = $request->name;
            $update['email'] = $request->email;

            if(!empty($request->new_password)){
                 $update['password'] = bcrypt($request->new_password);
            }

            // Updating model
            if (!$user->update($update)) {
                return Redirect::back()
                    ->withErrors(['Something wrong happened while saving your model'])
                    ->withInput();
            }

            // Account Updated,  redirect to index
            Session::flash('success', 'Account Succesfully Updated');
            return redirect()->action('AdminController@index');
        }

        return view('admin/edit_account')->with('data', $user);
    }

    public function addUserGet(){
        return view('admin/addUser');
    }

    public function addUserPost(Request $request){
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|confirmed:',
            'role' => 'required|in:user,admin'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role
        ]);
        Session::flash('success', 'User Created');
        return redirect('/admin');

    }

    /**
     * Edit User
     *
     * @param $request
     * @param $id
     * @return view
     */
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

    /**
     * Delete User
     *
     * @return redirect
     */
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

    /**
     * Departments Page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function departments(){
        $viewData['departments'] = Department::All();
    	return view('admin/departments')->with('data', $viewData);
    }

    /**
     * Add Department Page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addDepartment(Request $request){
        $owners = User::whereIn('role', ['admin', 'author'])->pluck('name', 'id');
        // Post Data
        if($request->isMethod('post')){
            // Validating Data
            $this->validate($request, [
                'name' => 'required|max:255|unique:departments,name',
                'owner' => 'required|integer|exists:users,id|unique:departments,owner_id',
            ]);
            // Saving Data
            $Department = new Department();
            $Department->name = $request->name;
            $Department->owner_id = $request->owner;
            if ($Department->save()) {
                // Creating Notification Message
                $message = 'Department "' . $Department->name . '" created. And You are owner of it.';

                // Sending Notification
                $this->__sendIndividualMessage(['msg' => $message, 'type' => 'toUser', 'to' => hash_hmac('SHA1', $Department->owner_id, 'A2888mTnk874MB')]);

                // Saving In Logs
                Log::createLog([
                    'action' => 'add_department',
                    'msg' => $message,
                    'user_id' => Auth::user()->id,
                    'user_name' => Auth::user()->name,
                ]);

                // Redirect with success flash message
                Session::flash('success', 'Department Saved Succesfully');
                return redirect()->action('AdminController@departments');
            }else{
                // Redirect with error flash message
                return Redirect::back()
                    ->withErrors(['Something wrong happened while saving your model'])
                    ->withInput();
            }
        }

        // Getting Id->Name Pair from DB >>>
        $viewData['owners'] = $owners;
        return view('admin/add_department')->with('data', $viewData);
    }

    /**
     * Edit Department Page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editDepartment(Request $request, $id){
        // Decoding Id
        $id = $this->decode($id);

        // Getting Department
        $department = Department::find($id);

        // Redirect with errors, if incorrect id has passed
        if(empty($department)){
            return Redirect::back()->withErrors(['Department Not Found']);
        }

        // Post Data
        if($request->isMethod('post')){
            // Validating Data
            $this->validate($request, [
                'name' => 'required|max:255|unique:departments,name,'.$id,
                'owner_id' => 'required|integer|exists:users,id|unique:departments,owner_id,'.$id,
            ]);

            // Saving Data
            if (!$department->update(Input::all())) {
                // Redirect with error flash message
                return Redirect::back()
                    ->withErrors(['Something wrong happened while saving your model'])
                    ->withInput();
            }

            // Creating Notification Message
            $message = 'Department "' . $department->name . '" Has been Updated.';

            // Sending Notification
            $this->__sendIndividualMessage(['msg' => $message,'type' => 'toUser', 'to' => hash_hmac('SHA1', $department->owner_id, 'A2888mTnk874MB')]);

            // Saving In Logs
            Log::createLog([
                'action' => 'update_department',
                'msg' => $message,
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
            ]);

            // Redirect with success flash message
            Session::flash('success', 'Department Successfully Updated');
            return redirect()->action('AdminController@departments');
        }

        // Getting All Admin permission Users
        $viewData['owners'] = User::whereIn('role', ['admin', 'author'])->pluck('name', 'id');

        // Creating View Data
        $viewData['department'] = $department;
        return view('admin/edit_department')->with('data', $viewData);
    }

    /**
     * Delete Department
     *
     * @return redirect to Departments
     */
    public function deleteDepartment($id){
        // Decoding Id
        $id = $this->decode($id);

        // Getting Department
        $department = Department::find($id);

        // Redirect with errors, if incorrect id has passed
        if(empty($department)){
            // Redirect with error flash message
            return Redirect::back()->withErrors(['Department Not Found']);
        }

        // Delete Department
        if($department->delete($id)){
            // Creating Notification Message
            $message = 'Department "' . $department->name . '" Has been Deleted';

            // Sending Notification
            $this->__sendIndividualMessage(['msg' => $message,'type' => 'toUser', 'to' => hash_hmac('SHA1', $department->owner_id, 'A2888mTnk874MB')]);

            // Saving In Logs
            Log::createLog([
                'action' => 'delete_department',
                'msg' => $message,
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
            ]);

            // Redirect with success flash message
            Session::flash('success', 'Department Succesfully Deleted');
            return redirect()->action('AdminController@departments');
        }
    }

    /**
     * Manage Tickets Page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tickets(){
        // Getting All Tickets groupped by status
        $Ticket = new Ticket();
        $tickets = $Ticket->where('status', 'pending')->paginate(10);
        $viewData['tickets'] = $tickets;

        return view('admin/tickets')->with('data', $viewData);

    }

    /**
     * Accept User Ticket
     *
     * @param ticket $id
     * @return redirect
     */
    public function acceptTicket($id){
        // Decoding $id
        $id = $this->decode($id);

        // Getting Ticket
        $ticket = Ticket::find($id);

        // Redirect with errors, if incorrect id has passed
        if(empty($ticket)){
            // Redirect with error flash message
            return Redirect::back()->withErrors(['Ticket Not Found']);
        }

        // Update Ticket
        if($ticket->update(['status' => 'accepted'])){
            // Creating Notification Message
            $message = 'Ticket "' . $ticket->name . '" Accepted';

            // Sending Notification
            $this->__sendIndividualMessage(['msg' => $message,'type' => 'toUser', 'to' => hash_hmac('SHA1', $ticket->department->owner->id, 'A2888mTnk874MB')]);

            // Saving In Logs
            Log::createLog([
                'action' => 'ticket_accepted',
                'msg' => $message,
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
            ]);

            // Redirect with success flash message
            Session::flash('success', 'Ticket Successfully Updated');
            return redirect()->action('AdminController@tickets');
        }

        return Redirect::back()->withErrors(['Something Goes Wrong']);
    }
}
