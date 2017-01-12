<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Image;

class Client extends Model
{
    protected $fillable = [
    	'phone',
    	'name',
    	'surname',
    	'passport_screen'
    ];

    public static function getByPhone($phone){
    	$client = self::where('phone', $phone)->first();
    	return empty($client) ? null : $client;
    }

    public static function createImg($file){
        // Creating Image From Base64 
        $img = substr($file, strpos($file, ",")+1);
        $data = base64_decode($img);
        // Picking up image name and path
        $png_url = "consumer-".time().".jpg";
        $path = public_path().'/img/consumers/' . $png_url;
        // Creating File
        $success = file_put_contents($path, $data);
        // Resizing File to save HDD space
        $returnData = public_path().'/img/consumers/' . $png_url;

        $image = Image::make(file_get_contents($returnData))
            ->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path);
        
        return '/img/consumers/' . $png_url;
    }
}
