@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Add User')

@section('content')
    @parent
    <div class="container">
        <h3> Add User </h3>
        <hr>
        <div class="row">
            {!! Form::open(['method' => 'post']) !!}
            <div class="text-center">
                <h6>Name</h6>
                {!! Form::text('name','', array('class' =>'form-control')) !!}
            </div>
            <div class="text-center form-group">
                <h6>Email (username)</h6>
                {!! Form::text('email','', array('class' =>'form-control')) !!}
            </div>
            <div class="text-center form-group">
                <h6>Password</h6>
                {!! Form::password('password',array('class' =>'form-control')) !!}
            </div>
            <div class="text-center form-group">
                <h6>Confirm Password</h6>
                {!! Form::password('password_confirmation',array('class' =>'form-control')) !!}
            </div>
            <div class="text-center">
                <h6>User Type</h6>

                <span>Slip assistant</span>
                {!! Form::radio('role','user', true , array('class' =>'radio-control')) !!}

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <span>Gift assistant</span>
                {!! Form::radio('role','admin', false, array('class' =>'radio-control')) !!}

                <br/><br/>
            </div>
            <div class="text-center">
                {!! Form::submit('Add User', array('class' => 'btn btn-primary')) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    
@endsection