@extends('layouts.app')

@section('title', 'Laravel - Edit Book')

@section('content')
    @parent
    @if(!empty($data['book']))
        <div class="container">
            <h3> Edit Book </h3>
            <hr>
            <div class="row">
            {!! Form::model($data['book'], ['action' => ['BooksController@editBook', Hashids::encode($data['book']->id)], 'method' => 'POST']) !!}
                <div class="text-center">
                    <h6>Book Name</h6>
                   {!! Form::text('name', null, array('placeholder'=>'Book Name', 'class' =>'form-control')) !!}
                </div>
                <div class="text-center">
                    <h6>Book Pages</h6>
                   {!! Form::text('pages', null , array('placeholder'=>'Book Pages', 'class' =>'form-control')) !!}
                </div>
                <div class="text-center">
                    {!! Form::submit('Edit Book', array('class' => 'btn brownBtn')) !!}
               </div>
            {!! Form::close() !!}
            </div>
        </div>
    @endif
@endsection