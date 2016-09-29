@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Edit Account')

@section('content')
    @parent
    @if(!empty($data))
        <div class="container">
            <h3> Edit Account </h3>
            <hr>
            <div class="row">
                {!! Form::model($data, ['action' => ['AdminController@editAccount', Hashids::encode($data->id)]]) !!}
                <div class="text-center">
                    <h6>User Name</h6>
                    {!! Form::text('name', null, array('placeholder'=>'Picture Name', 'class' =>'form-control')) !!}
                </div>
                <div class="text-center">
                    <h6>User Email</h6>
                    {!! Form::text('email', null , array('placeholder'=>'User Email', 'class' =>'form-control')) !!}
                </div>
                <div class="text-center">
                    <h6>Old Password</h6>
                    {!! Form::password('password', array('placeholder'=>'Old Password', 'class' =>'form-control')) !!}
                </div>
                <div class="text-center">
                    <h6>New Password</h6>
                    {!! Form::password('new_password', array('placeholder'=>'New Password', 'class' =>'form-control')) !!}
                </div>
                <div class="text-center">
                    <h6>Confirm Password</h6>
                    {!! Form::password('new_password_confirmation', array('placeholder'=>'Confirm Password', 'class' =>'form-control')) !!}
                </div>
                <div class="text-center">
                    {!! Form::submit('Save', array('class' => 'btn brownBtn')) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    @endif
@endsection