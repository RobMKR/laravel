@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Add Department')

@section('content')
    @parent
    <div class="container">
        <h3> Add Department </h3>
        <hr>
        <div class="row">
            {!! Form::open(['method' => 'post']) !!}
            <div class="text-center">
                <h6>Department Name</h6>
                {!! Form::text('name', '', array('placeholder'=>'Department Name', 'class' =>'form-control')) !!}
            </div>
            <div class="text-center form-group">
                <h6>Department Owner (From Existing Users)</h6>
                {!! Form::select('owner', $data['owners'] , null, array('placeholder' => 'Select Owner', 'class' =>'form-control')) !!}
            </div>
            <div class="text-center">
                {!! Form::submit('Add Department', array('class' => 'btn brownBtn')) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection