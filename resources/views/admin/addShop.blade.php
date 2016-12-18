@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Shops')

@section('content')
    @parent
    <div class="container">
        <h3> Add Shop </h3>
        <hr>
        <div class="row">
            {!! Form::open(['method' => 'post']) !!}
            <div class="text-center">
                <h6>Shop Short Name</h6>
                {!! Form::text('short_name','', array('class' =>'form-control')) !!}
            </div>
            <div class="text-center form-group">
                <h6>Shop Full Name </h6>
                {!! Form::text('full_name','', array('class' =>'form-control')) !!}
            </div>
            <div class="text-center">
                {!! Form::submit('Add Shop', array('class' => 'btn btn-primary')) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    
@endsection