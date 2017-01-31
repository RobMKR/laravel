<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Shop;
use App\Gift;
use App\ShopGift;
use App\Slip;
use App\ClientGift;
use App\Week;
use App\Config;
use DB;
use Session;
use \GuzzleHttp;
use \Exception;
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
            ->select('shops.id as shop_id', 'gifts.id as gift_id', DB::raw('(shop_gifts.count - shop_gifts.used) as count'))
            ->get();

        foreach($counts->toArray() as $_count){
        	$viewData['counts'][$_count['shop_id']][$_count['gift_id']] = $_count['count'];
        }

    	return view('admin/giftShops')->with('data', $viewData);
    }

    public function consumerSlips(){
        $Slip = new Slip();
        $viewData['slips'] = $Slip
            ->join('clients', 'clients.id', '=', 'client_week_slips.client_id')
            ->join('weeks', 'weeks.id', '=', 'client_week_slips.week_id')
            ->paginate(50);

        return view('admin/consumerSlips')->with('data', $viewData);   
    }

    public function consumerAvailableGifts(){
        $ClientGift = new ClientGift();
        $viewData['weeks'] = Week::get();

        $viewData['gifts'] = $ClientGift
            ->whereNull('gift_id')
            ->whereNull('reserved_id')
            ->join('clients', 'clients.id', '=', 'client_gift_weeks.client_id')
            ->join('weeks', 'weeks.id', '=', 'client_gift_weeks.week_id')
            ->join('shops', 'shops.id', '=', 'client_gift_weeks.shop_id')
            ->orderBy('weeks.id')
            ->orderBy('shops.short_name', 'ASC')
            ->paginate(50);
        return view('admin/consumerAvailableGifts')->with('data', $viewData); 
    }

    public function consumerTakenGifts(){
        $array = [];
        $client_gifts = ClientGift::
            whereNotNull('gift_id')
            ->orWhereNotNull('reserved_id')
            ->join('clients', 'clients.id', '=', 'client_gift_weeks.client_id')
            ->join('weeks', 'weeks.id', '=', 'client_gift_weeks.week_id')
            ->join('shops', 'shops.id', '=', 'client_gift_weeks.shop_id')
            ->select(
                'client_gift_weeks.id as Id',
                'shops.id as ShopId', 
                'shops.short_name as ShopName', 
                'weeks.id as WeekId', 
                'weeks.start as WeekStart',
                'weeks.end as WeekEnd', 
                'clients.id as ClientId',
                'clients.phone as Phone',
                'clients.name as Name',
                'clients.surname as Surname',
                'clients.birth_date as DOB',
                'clients.passport_id as Passport'
            )
            ->get()->keyBy(['Id']);

        foreach($client_gifts as $_gift){
            if(!array_key_exists($_gift->ClientId, $array)){
                $array[$_gift->ClientId]['shop_id'] = $_gift->ShopId;
                $array[$_gift->ClientId]['shop_name'] = $_gift->ShopName;
                $array[$_gift->ClientId]['week_id'] = $_gift->WeekId;
                $array[$_gift->ClientId]['week_name'] = date(
                    'd F Y', 
                    strtotime($_gift->WeekStart)) . ' - ' . date('d F Y', strtotime($_gift->WeekEnd) + 1
                );
                $array[$_gift->ClientId]['phone'] = $_gift->Phone;
                $array[$_gift->ClientId]['name'] = $_gift->Name;
                $array[$_gift->ClientId]['surname'] = $_gift->Surname;
                $array[$_gift->ClientId]['birth_date'] = $_gift->DOB;
                $array[$_gift->ClientId]['passport'] = $_gift->Passport;                
            }
        }

        return view('/admin/consumerTakenGifts')->with('data', $array);

        
    }

    public function getGiftsAjax(Request $request){
        $id = $request->id;

        $data['late_gifts'] = ClientGift::where('client_id', $id)
            ->where('reserved_id', 0)
            ->count();

        $data['taken_gifts'] = ClientGift::where('client_id', $id)
            ->whereNotNull('gift_id')
            ->join('gifts', 'gifts.id', '=', 'client_gift_weeks.gift_id')
            ->select('gifts.name as GiftName' , 'gifts.id as GiftId')
            ->get()->keyBy('GiftId')->toArray();

        $data['reserved_gifts'] = ClientGift::where('client_id', $id)
            ->whereNotIn('reserved_id', array_keys($data['taken_gifts']))
            ->whereNotNull('reserved_id')
            ->join('gifts', 'gifts.id', '=', 'client_gift_weeks.reserved_id')
            ->select('gifts.name as GiftName', 'gifts.id as GiftId')
            ->get()->keyBy('GiftId')->toArray();

        return response()->json(['data' => $data]);
    }

    public function sendSmsSendbox(Request $request){

        $week_id = $request->week;

        $client_gifts = ClientGift::where('week_id', $week_id)
            ->whereNull('gift_id')
            ->whereNull('reserved_id')
            ->orderBy('last_slip_date', 'DESC')
            ->get();

        foreach($client_gifts as $client_gift){
            $gift_order = ClientGift::where('client_id', $client_gift->client_id)
                ->where(function($q){
                    $q->whereNotNull('gift_id');
                    $q->orWhereNotNull('reserved_id');
                })
                ->count() + 1;
            $gift_id = Gift::where('week_order', $gift_order)->first()['id'];
			if($gift_id == null){
				$client_gift->reserved_id = 0;
                $client_gift->save();
				continue;
			}

            $ShopGift = ShopGift::where('shop_id', $client_gift->shop_id)->where('gift_id', $gift_id)->first();
            if(($ShopGift->count - $ShopGift->used) > 0){
                $ShopGift->increment('used');
                $client_gift->reserved_id = $gift_id;
                $client_gift->save();
            }else{
                $client_gift->reserved_id = 0;
                $client_gift->save();
            }
        }

        Session::flash('success', 'Gifts Reserved for consumers');
        return redirect()->back();

    }

    public function sendSmsReal(Request $request){
        $week_id = $request->week;

        /*************************************************************************************/
        $login_url = 'https://api.mobipace.com:444/v3/authorize';
        
        $fields = array(
            'Username' => 'SUPER AKCIA',
            'Password' => 'jti2016'
        );

        $ch = curl_init($login_url);
        
        if (FALSE === $ch)
            throw new Exception('failed to initialize');

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_URL, $login_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($fields));

        $output = json_decode(curl_exec ($ch));
        if($output->StatusCode != 101){
            throw new Exception("Cannot Send SMS, Contact to your developer as soon as possible");
        }

        $session_id = $output->SessionId;
        /*************************************************************************************/

        /*************************************************************************************/
        $send_url = 'https://api.mobipace.com:444/v3/Send';
        $sms_params = [
            'SessionId' => $session_id,
            'Sender' => 'Winston',
            'Messages' => []
        ];
		
		$sms_params['Messages'][] = [
			'Recipient' => 37499414125,
			'Body' => 'Test check. OK!'
		];
		
		$sms_params['Messages'][] = [
			'Recipient' => 37455607104,
			'Body' => 'Test check. OK!'
		];
		
        $sms_texts = Config::find(1);
		
		$not_enough = Slip::where('week_id', $week_id)
            ->where('slip_count', '<', 7)
			->join('clients', 'clients.id', '=', 'client_week_slips.client_id')
			->select('client_week_slips.*',
                'clients.name as CName', 
                'clients.surname as CSurName',
                'clients.phone as CPhone')
            ->get();
			
		foreach($not_enough as $_client){
			// Case when slips < 7
			$sms_text = $sms_texts->sms_not_enough;
			$sms_text = str_replace('[!NAME!]', $_client->CName . ' ' . $_client->CSurName, $sms_text);

			$sms_params['Messages'][] = [
				'Recipient' => 374 . $_client->CPhone,
				'Body' => $sms_text
			];
		}
		
        $client_gifts = ClientGift::where('week_id', $week_id)
            ->whereNull('gift_id')
            ->whereNull('reserved_id')
            ->join('clients', 'clients.id', '=', 'client_gift_weeks.client_id')
            ->join('shops', 'shops.id', '=', 'client_gift_weeks.shop_id')
            ->select('client_gift_weeks.*',
                'shops.full_name as ShopName',
                'shops.time as time' ,
                'clients.name as CName', 
                'clients.surname as CSurName',
                'clients.phone as CPhone')
            ->orderBy('last_slip_date', 'DESC')
            ->get();
        if($client_gifts->isEmpty()){
            throw new Exception("No Participants found in week");
        }

        foreach($client_gifts as $client_gift){
            $gift_order = ClientGift::where('client_id', $client_gift->client_id)
                ->where(function($q){
                    $q->whereNotNull('gift_id');
                    $q->orWhereNotNull('reserved_id');
                })
                ->count() + 1;

            $gift = Gift::where('week_order', $gift_order)->first();
			$gift_id = $gift['id'];

            if($gift_id == null){
                // Case when already taken all gifts
                $sms_text = $sms_texts->sms_all_taken;
                $sms_text = str_replace('[!NAME!]', $client_gift->CName . ' ' . $client_gift->CSurName, $sms_text);

                $sms_params['Messages'][] = [
                    'Recipient' => 374 . $client_gift->CPhone,
                    'Body' => $sms_text
                ];

                $client_gift->reserved_id = 0;
                $client_gift->save();
                continue;
            }

            $ShopGift = ShopGift::where('shop_id', $client_gift->shop_id)->where('gift_id', $gift_id)->first();
            if(($ShopGift->count - $ShopGift->used) > 0){
                // Case when gift reserved
                $sms_text = $sms_texts->sms_ok;
                $sms_text = str_replace('[!NAME!]', $client_gift->CName . ' ' . $client_gift->CSurName, $sms_text);
                $sms_text = str_replace('[!SHOP!]', $client_gift->ShopName, $sms_text);
                $sms_text = str_replace('[!TIME!]', $client_gift->time, $sms_text);
				$sms_text = str_replace('[!GIFT!]', $gift->name, $sms_text);

                $sms_params['Messages'][] = [
                    'Recipient' => 374 . $client_gift->CPhone,
                    'Body' => $sms_text
                ];

                $ShopGift->increment('used');
                $client_gift->reserved_id = $gift_id;
                $client_gift->save();
            }else{
                // Case when gifts are 0
                $sms_text = $sms_texts->sms_no_gift;
                $sms_text = str_replace('[!NAME!]', $client_gift->CName . ' ' . $client_gift->CSurName, $sms_text);

                $sms_params['Messages'][] = [
                    'Recipient' => 374 . $client_gift->CPhone,
                    'Body' => $sms_text
                ];

                $client_gift->reserved_id = 0;
                $client_gift->save();
            }
        }
		
        $ch = curl_init($send_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_URL, $send_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($sms_params));

        $output = json_decode(curl_exec ($ch));

        if($output->StatusCode != 101){
            throw new Exception("Cannot Send SMS, Contact to your developer as soon as possible");
            
        }

        Session::flash('success', 'Gifts Reserved for consumers, Messages send');
        return redirect()->back();

        /*************************************************************************************/
    }
}
