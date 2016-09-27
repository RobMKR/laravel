<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Book as Book;
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
        $viewData =  Book::separeteBooks(Book::all(), Auth::user()->id);
        return view('home')->with('data', $viewData);
    }

    public function chat(){   
        return view('chat');
    }

    public function sendMessage(Request $request){
        $redis = LRedis::connection();
        $data = ['message' => $request->input('message'), 'user' => $request->input('user'), 'user_hashed' => sha1(Auth::user()->id)];
        $redis->publish('message', json_encode($data));
        return response()->json([]);
    }
}
