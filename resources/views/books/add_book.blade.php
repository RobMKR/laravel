@extends('layouts.app')

@section('title', 'Laravel - Add Book')

@section('content')
    @parent
    <div class="container">
        <h3> Add Book </h3>
        <hr>
        <div class="row">
        {!! Form::open(['method' => 'post']) !!}
            <div class="text-center">
                <h6>Book Name</h6>
               {!! Form::text('name', '', array('placeholder'=>'Book Name', 'class' =>'form-control')) !!}
            </div>
             <div class="text-center">
                <h6>Book Pages</h6>
               {!! Form::text('pages', '', array('placeholder'=>'Book Pages', 'class' =>'form-control')) !!}
            </div>
            <div class="text-center">
                {!! Form::submit('Add Book', array('class' => 'btn brownBtn')) !!}
           </div>
        {!! Form::close() !!}
        </div>
    </div>
@endsection