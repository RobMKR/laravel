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
                   {!! Form::text('email', null , array('placeholder'=>'User Email', 'class' =>'form-control')) !!}
                </div>
                <div class="text-center">
                    <h6>User Type</h6>

                    <span>Slip assistant</span>
                    {!! Form::radio('role','user', null , array('class' =>'radio-control')) !!}

                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                    <span>Gift assistant</span>
                    {!! Form::radio('role','admin', null, array('class' =>'radio-control')) !!}

                    <br/><br/>
                </div>
                <div class="text-center">
                    {!! Form::submit('Edit User', array('class' => 'btn brownBtn')) !!}
               </div>
            {!! Form::close() !!}
            </div>
        </div>
    @endif
@endsection