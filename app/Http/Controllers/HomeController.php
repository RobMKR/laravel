<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use App\Client;
use App\Shop;
use App\Week;
use App\Slip;
use App\ClientGift;

class HomeController extends Controller
{   

    protected $redirectAfter = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function login(){
        if(Auth::check()){
            switch(Auth::user()->getLevel()){
                case 3 :
                    $this->redirectAfter = '/admin';
                    break;
                case 2 :
                    break;
                case 1 : 
                    $this->redirectAfter = '/slip/home';
                    break;
            }

            return redirect($this->redirectAfter);
        }
        return view('welcome');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $shops = Shop::pluck('short_name', 'id');
        return view('home')->with('shops', $shops);
    }

    public function addSlip(Request $request){
        if($request->type === 'find'){
            $this->validate($request, [
                'phone' => 'required|numeric',
                'date' => 'required',
                'time' => 'required',
                'shop_id' => 'required',
            ]);

            $client = Client::getByPhone($request->phone);

            if($client === null){
                return response()->json(['status' => 421, 'user' => null]);
            }
        }elseif($request->type === 'register'){
            $this->validate($request, [
                'phone' => 'required|numeric',
                'name' => 'required',
                'surname' => 'required',
                'shop_id' => 'required',
                'date' => 'required',
                'time' => 'required'
            ]);

            $client = new Client();
            $client->phone = $request->phone;
            $client->name = $request->name;
            $client->surname = $request->surname;

            $client->save();
        }

        $new_date = date("Y-m-d", strtotime(str_replace('/', '-', $request->date)));

        $week_id = Week::getWeekId($new_date . ' ' . $request->time);

        if($week_id === null){
            return response()->json(['status' => 404, 'err' => 'Տվյալ ամսաթվով խաղարկություն չի գտնվել']);
        }

        $slip = Slip::where('client_id', $client['id'])->where('week_id', $week_id)->first();
        
        if(!$slip){
            $slip = new Slip();

            $slip->client_id = $client['id'];
            $slip->week_id = $week_id;
            $slip->slip_count = $request->count;

            $slip->save();

            $msg = 'Կտրոնների քանակ : ' . $request->count;
        }else{
            $overall = $slip->slip_count + (int) $request->count;
            $msg = 'Կտրոնների քանակ : ' . $overall;

            if($slip->slip_count < 7 && $overall > 6){
                $ClientGift = new ClientGift();

                $ClientGift->client_id = $client->id;
                $ClientGift->shop_id = $request->shop_id;
                $ClientGift->week_id = $week_id;

                $ClientGift->save();

                $msg .= ', Հաճախորդը հավաքեց պահանջված միավորների քանակ';
            }

            $slip->increment('slip_count', (int) $request->count);                                  
        }

        return response()->json(['status' => 200, 'msg' => $msg]);
    }


}
