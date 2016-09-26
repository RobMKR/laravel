@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Edit User')

@section('content')
    @parent
    @if(!empty($data['user']))
        <div class="container">
            <h3> Edit User </h3>
            <hr>
            <div class="row">
            {!! Form::model($data['user'], ['action' => ['AdminController@editUser', Hashids::encode($data['user']->id)]]) !!}
                <div class="text-center">
                    <h6>User Name</h6>
                   {!! Form::text('name', null, array('placeholder'=>'Picture Name', 'class' =>'form-control')) !!}
                </div>
                <div class="text-center">
                    <h6>User Email</h6>
                   {!! Form::text('email', null , array('placeholder'=>'Picture Description', 'class' =>'form-control')) !!}
                </div>
                <div class="form-group text-center">
                    <h6>User Role</h6>
                    {!! Form::select('role', $data['roles'] ,null, array('placeholder' => 'Select Role', 'class' =>'form-control')) !!}
                </div>
                <div class="text-center">
                    {!! Form::submit('Edit User', array('class' => 'btn brownBtn')) !!}
               </div>
            {!! Form::close() !!}
            </div>
        </div>
    @endif
@endsection