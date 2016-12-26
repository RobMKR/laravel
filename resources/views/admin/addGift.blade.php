@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Add Gift')

@section('content')
    @parent
    <div class="container">
        <h3> Add Gift </h3>
        <hr>
        <div class="row">
            {!! Form::open(['method' => 'post', 'files' => true]) !!}
            <div class="text-center">
                <h6>Gift Name</h6>
                {!! Form::text('name','', array('class' =>'form-control')) !!}
            </div>
            <div class="text-center">
                <h6>Gift Type</h6>

                <span>Image (Size - 64x64)</span>
                {!! Form::radio('icon_type','url', true , array('class' =>'radio-control')) !!}
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <span>Font Awesome Class</span>
                {!! Form::radio('icon_type','class', false, array('class' =>'radio-control')) !!}
                <br/><br/>
            </div>

            <div class="text-center">
                {!! Form::file('icon_url', array('class' =>'form-control')) !!}
            </div>

            <div class="text-center">
                <h6>Gift class name</h6>
                {!! Form::text('icon_class','', array('class' =>'form-control')) !!}
            </div>
            <br>
            <div class="text-center">
                {!! Form::submit('Add Gift', array('class' => 'btn btn-primary')) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    
@endsection