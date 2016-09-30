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
                $this->__sendIndividualMessage(['msg' => $message,'type' => 'toAdmin', 'to' => hash_hmac('SHA1', $this->superAdmin()->id, 'A2888mTnk874MB'), 'from' => 'System']);

                // Saving In Logs
                Log::createLog([
                    'action' => 'add_ticket',
                    'msg' => $message,
                    'user_id' => Auth::user()->id,
                    'user_name' => Auth::user()->name,
                ]);

                // Sending Notification Email to superadmin
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

    /**
     * Remove Ticket Page
     *
     *
     *
     * @param Request
     * @param id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request , $id){
        // Decoding id
        $id = $this->decode($id);

        // Getting Ticket
        $ticket = Ticket::find($id);

        // Redirect with errors, if incorrect id has passed
        if(empty($ticket)){
            // Redirect with error flash message
            return Redirect::back()->withErrors(['Ticket Not Found']);
        }

        // Post Data
        if($request->isMethod('post')){
            // Validating Data
            $this->validate($request, [
                'name' => 'required|max:255|unique:tickets,name,'.$id,
                'description' => 'required',
                'department_id' => 'required|integer|exists:departments,id',
            ]);

            // Saving Data
            if (!$ticket->update(Input::all())) {
                // Redirect with error flash message
                return Redirect::back()
                    ->withErrors(['Something wrong happened while saving your model'])
                    ->withInput();
            }

            // Creating Notification Message
            $message = 'Ticket "' . $ticket->name . '" Has been Updated.';

            // Sending Notification
            $this->__sendIndividualMessage(['msg' => $message,'type' => 'toAdmin', 'to' => hash_hmac('SHA1', $ticket->department->owner_id, 'A2888mTnk874MB')]);

            // Saving In Logs
            Log::createLog([
                'action' => 'update_department',
                'msg' => $message,
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
            ]);

            // Redirect with success flash message
            Session::flash('success', 'Ticket Successfully Updated');
            return redirect()->action('HomeController@tickets');
        }

        // Creating View Data
        $viewData['ticket'] = $ticket;
        $viewData['departments'] = Department::pluck('name', 'id');
        return view('tickets/edit_ticket')->with('data', $viewData);

    }

    /**
     * Edit Ticket Page
     *
     * @return redirect to tickets page
     */
    public function delete($id){
        // Decoding id
        $id = $this->decode($id);

        // Getting Ticket
        $ticket = Ticket::find($id);

        // Redirect with errors, if incorrect id has passed
        if(empty($ticket)){
            // Redirect with error flash message
            return Redirect::back()->withErrors(['Ticket Not Found']);
        }

        // Delete Department
        if($ticket->delete($id)){
            // Creating Notification Message
            $message = 'Ticket "' . $ticket->name . '" Has been Deleted';

            // Sending Notification
            $this->__sendIndividualMessage(['msg' => $message,'type' => 'toAdmin', 'to' => hash_hmac('SHA1', $ticket->department->owner_id, 'A2888mTnk874MB')]);

            // Saving In Logs
            Log::createLog([
                'action' => 'delete_ticket',
                'msg' => $message,
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
            ]);

            // Redirect with success flash message
            Session::flash('success', 'Ticket Succesfully Deleted');
            return redirect()->action('HomeController@tickets');
        }

    }
}
