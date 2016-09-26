<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User as User;

class Book extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'pages', 'author_id',
    ];

	public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function separeteBooks($books, $user){
    	$return = [
    		'my_books' => [],
    		'other_books' => []
    	];
    	if(empty($books)){
    		return $return;
    	}
    	foreach($books as $book){
    		if($book->user_id === $user){
    			$return['my_books'][] = $book;
    		}else{
    			$return['other_books'][] = $book;
    		}
    	}
    	return $return;
    }
}
