@extends('layouts.app')

@section('title', 'Laravel - Home')

@section('content')
<div class="container">
    @if(!empty($data['my_books']))
        <div class="row">
            <h2 class="text-center">My Books</h2>
            @foreach($data['my_books'] as $book)                
                <li class="userInfo list-group-item text-center">
                    <span>
                        <strong>Name: </strong>{{$book->name}} |
                        <strong>Pages: </strong>{{$book->pages}} |
                        <strong>Author Name: </strong>{{$book->user->name}} |
                        <strong>Author Email: </strong>{{$book->user->email}} |
                        <strong>Created: </strong>{{$book->created_at}} 
                    </span>
                    @if(Auth::user()->getLevel())
                        <a href="{{url('/books/editBook/' . Hashids::encode($book->id))}}" class="glyphicon glyphicon-pencil"></a>
                        <a href="{{url('/books/deleteBook/' . Hashids::encode($book->id))}}" class="glyphicon glyphicon-remove"></a>
                    @endif
                </li>
            @endforeach
        </div>
        <hr>
    @endif
    @if(!empty($data['other_books']))
        <div class="row">
            <h2 class="text-center">Other Books</h2>
            @foreach($data['other_books'] as $book)
                <li class="userInfo list-group-item text-center">
                    <span>
                        <strong>Name: </strong>{{$book->name}} |
                        <strong>Pages: </strong>{{$book->pages}} |
                        <strong>Author Name: </strong>{{$book->user->name}} |
                        <strong>Author Email: </strong>{{$book->user->email}} 
                    </span>
                </li>
            @endforeach
        </div>
        <hr>
    @endif
</div>
@endsection
