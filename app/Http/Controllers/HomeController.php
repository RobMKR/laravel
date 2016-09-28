<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Department as Department;
use App\Ticket as Ticket;
use LRedis;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('home');
    }
    /**
     * SuperAdmin SendMessage Action (via WS)
     *
     * @return json
     */
    public function sendMessage(Request $request){
        $user_id = hash_hmac('SHA1', '4', 'A2888mTnk874MB');
        $redis = LRedis::connection();
        $data = ['message' => $request->input('message'), 'user' => $request->input('user'), 'user_hashed' => sha1(Auth::user()->id), 'to' => $user_id];
        $redis->publish('message', json_encode($data));
        return response()->json([]);
    }

    /**
     * Departments Page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function departments(){
        $viewData['departments'] = Department::All();
        return view('departments')->with('data', $viewData);
    }

    /**
     * Tickets Page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tickets(){
        $viewData['tickets'] = Ticket::All();
        return view('tickets')->with('data', $viewData);
    }
}
