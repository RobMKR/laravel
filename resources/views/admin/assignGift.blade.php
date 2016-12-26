@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Assign Gifts')

@section('content')
    @parent
    <div class="container">
        <h3> Add Gifts To shop </h3>
        <hr>
        {!! Form::open(['method' => 'post']) !!}
        <div class="row">
            
            <div class="text-center col-md-12">
                {!! Form::select('shop_id',$data['shops'],Request::get('s'), array('class' =>'form-control', 'placeholder' => 'Select Shop')) !!}
            </div>
            <div class="text-center col-md-12">
                {!! Form::select('gift_id',$data['gifts'],Request::get('g'), array('class' =>'form-control', 'placeholder' => 'Select Gift')) !!}
            </div>
            <div class="text-center col-md-12">
                {!! Form::text('count','', array('class' =>'form-control' , 'placeholder' => 'Select Gift Count')) !!}
            </div>
            <div class="text-center col-md-12">
                {!! Form::submit('Add Gifts To Shop', array('class' => 'btn btn-primary')) !!}
            </div>
            
        </div>
        {!! Form::close() !!}
    </div>
    
@endsection