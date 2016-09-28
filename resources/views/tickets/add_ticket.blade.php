@extends('layouts.app')

@section('title', 'Laravel - Add Ticket')

@section('content')
    @parent
    <div class="container">
        <h3> Add Ticket </h3>
        <hr>
        <div class="row">
            {!! Form::open(['method' => 'post']) !!}
            <div class="text-center">
                <h6>Ticket Name</h6>
                {!! Form::text('name', '', array('placeholder'=>'Ticket Name', 'class' =>'form-control')) !!}
            </div>
            <div class="text-center">
                <h6>Ticket Description</h6>
                {!! Form::textarea('description', '', array('placeholder'=>'Ticket Description', 'class' =>'form-control')) !!}
            </div>
            <div class="text-center form-group">
                <h6>Department</h6>
                {!! Form::select('department_id', $data['departments'] , null, array('placeholder' => 'Select Department', 'class' =>'form-control')) !!}
            </div>
            <div class="text-center">
                {!! Form::submit('Add Ticket', array('class' => 'btn brownBtn')) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection