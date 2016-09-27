<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\Book as Book;
use App\NotificationLog as Log;
use App\Http\Requests;
use Session;

class BooksController extends Controller
{
    public function addBook(Request $request){
    	// Post Data
    	if($request->isMethod('post')){
    		// Validating Data
			$this->validate($request, [
	            'name' => 'required|max:255|unique:books,name',
	            'pages' => 'required|digits_between:2,4',
	        ]);

			// Saving Data
            $Book = new Book();
            $Book->name = $request->name;
            $Book->pages = $request->pages;
            $Book->user_id = Auth::user()->id;
	        if ($Book->save()) {
                // Creating Notification Message
                $message = 'Book "' . $Book->name . '" Has been Created';

                // Sending Notification
	        	$this->__sendMessage(['msg' => $message, 'usr' => Auth::user()->name]);

                // Saving In Logs
                Log::createLog([
                    'action' => 'add_book',
                    'msg' => $message,
                    'user_id' => Auth::user()->id,
                    'user_name' => Auth::user()->name,
                ]);

                // Redirect with success flash message
        		Session::flash('success', 'Book Saved Succesfully');
    			return redirect()->action('HomeController@index');
    		}else{
                // Redirect with error flash message
    			return Redirect::back()
                ->withErrors(['Something wrong happened while saving your model'])
                ->withInput();
    		}
    	}
    	return view('books/add_book');
    }

    public function editBook(Request $request, $id){
    	// Decoding Id
    	$id = $this->decode($id);

    	// Getting User
		$book = Book::find($id);

		// Redirect with errors, if incorrect id has passed
    	if(empty($book) || !$this->checkAuthor($book->user_id)){
    		return Redirect::back()->withErrors(['Book Not Found']);
    	}
    	// Post Data
    	if($request->isMethod('post')){
    		// Validating Data
			$this->validate($request, [
	            'name' => 'required|max:255|unique:books,name,'. $id,
	            'pages' => 'required|digits_between:2,4',
	        ]);

			if (!$book->update(Input::all())) {
                // Redirect with error flash message
        		return Redirect::back()
                ->withErrors(['Something wrong happened while saving your model'])
                ->withInput();
    		}

            // Creating Notification Message
            $message = 'Book "' . $book->name . '" Has been Edited';

            // Sending Notification
    		$this->__sendMessage(['msg' => $message,  'usr' => Auth::user()->name]);

            // Saving In Logs
            Log::createLog([
                'action' => 'edit_book',
                'msg' => $message,
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
            ]);

            // Redirect with success flash message
    		Session::flash('success', 'Book Succesfully Updated');
    		return redirect()->action('HomeController@index');
    	}

    	// Creating View Data
    	$viewData['book'] = $book;

    	return view('books/edit_book')->with('data', $viewData);
    }

    public function deleteBook($id){
    	// Decoding Id
    	$id = $this->decode($id);

    	// Getting Book
		$book = Book::find($id);

		// Redirect with errors, if incorrect id has passed
    	if(empty($book) || !$this->checkAuthor($book->user_id)){
            // Redirect with error flash message
    		return Redirect::back()->withErrors(['Book Not Found']);
    	}

    	if($book->delete($id)){
            // Creating Notification Message
            $message = 'Book "' . $book->name . '" Has been Deleted';

            // Sending Notification
    		$this->__sendMessage(['msg' => $message, 'usr' => Auth::user()->name]);

            // Saving In Logs
            Log::createLog([
                'action' => 'delete_book',
                'msg' => $message,
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
            ]);

            // Redirect with success flash message
    		Session::flash('success', 'Book Succesfully Deleted');
    		return redirect()->action('HomeController@index');
    	}
    }
}
