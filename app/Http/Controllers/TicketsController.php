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
use Mail;

class TicketsController extends Controller
{
    /**
     * Add Ticket Page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(Request $request){
        // Post Data
        if($request->isMethod('post')){
            // Validating Data
            $this->validate($request, [
                'name' => 'required|max:255|unique:tickets,name',
                'description' => 'required',
                'department_id' => 'required|integer|exists:departments,id',
            ]);

            // Saving Data
            $Ticket = new Ticket();
            $Ticket->name = $request->name;
            $Ticket->description = $request->description;
            $Ticket->department_id = $request->department_id;
            $Ticket->user_id = Auth::user()->id;
            $Ticket->status = 'pending';

            if ($Ticket->save()) {
                // Creating Notification Message
                $message = 'User "' . $Ticket->user->name . '" created a Ticket: "' . $Ticket->name . '" into Department: "' . $Ticket->department->name . '"';

                // Sending Notification
                $this->__sendIndividualMessage(['msg' => $message, 'to' => hash_hmac('SHA1', $this->superAdmin()->id, 'A2888mTnk874MB'), 'from' => 'System']);

                // Saving In Logs
                Log::createLog([
                    'action' => 'add_ticket',
                    'msg' => $message,
                    'user_id' => Auth::user()->id,
                    'user_name' => Auth::user()->name,
                ]);

               $this->send('test', 'test', $this->superadmin()->email);

                // Redirect with success flash message
                Session::flash('success', 'Ticket Successfully Added!');
                return redirect()->action('HomeController@tickets');
            }else{
                // Redirect with error flash message
                return Redirect::back()
                    ->withErrors(['Something wrong happened while saving your model'])
                    ->withInput();
            }
        }

        // Getting All Departments
        $viewData['departments'] = Department::pluck('name', 'id');

        return view('tickets/add_ticket')->with('data', $viewData);
    }
}
