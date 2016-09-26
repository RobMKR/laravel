<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\Book as Book;
use App\Http\Requests;
use Session;

class BooksController extends Controller
{
    public function editBook(){
    	return view('books/edit_book');
    }

    public function addBook(Request $request){
    	// Post Data
    	if($request->isMethod('post')){
    		// Validating Data
			$this->validate($request, [
	            'name' => 'required|max:255|unique:books,name',
	            'pages' => 'required|max:255',
	        ]);

			// Saving Data
            $Book = new Book();
            $Book->name = $request->name;
            $Book->pages = $request->pages;
            $Book->user_id = Auth::user()->id;
	        if ($Book->save()) {
        		Session::flash('success', 'Book Saved Succesfully');
    			return redirect()->action('HomeController@index');
    		}else{
    			return Redirect::back()
                ->withErrors(['Something wrong happened while saving your model'])
                ->withInput();
    		}
    	}
    	return view('books/add_book');
    }
}
