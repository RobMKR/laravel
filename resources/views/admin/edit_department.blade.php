@extends('layouts.app')

@section('title', 'Admin Panel - Edit Department')

@section('content')
    @parent
    @if(!empty($data['department']))
        <div class="container">
            <h3> Edit Book </h3>
            <hr>
            <div class="row">
                {!! Form::model($data['department'], ['action' => ['AdminController@editDepartment', Hashids::encode($data['department']->id)], 'method' => 'POST']) !!}
                <div class="text-center">
                    <h6>Department Name</h6>
                    {!! Form::text('name', null, array('placeholder'=>'Department Name', 'class' =>'form-control')) !!}
                </div>
                <div class="text-center form-group">
                    <h6>Department Owner (From Existing Users)</h6>
                    {!! Form::select('owner_id', $data['owners'] , null , array('placeholder' => 'Select Owner', 'class' =>'form-control')) !!}
                </div>
                <div class="text-center">
                    {!! Form::submit('Edit Book', array('class' => 'btn brownBtn')) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    @endif
@endsection