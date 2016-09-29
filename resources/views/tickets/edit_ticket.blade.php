@extends('layouts.app')

@section('title', 'Laravel - Edit Ticket')

@section('content')
    @parent
    @if(!empty($data['ticket']))
        <div class="container">
            <h3> Edit Book </h3>
            <hr>
            <div class="row">
                {!! Form::model($data['ticket'], ['action' => ['TicketsController@edit', Hashids::encode($data['ticket']->id)], 'method' => 'POST']) !!}
                <div class="text-center">
                    <h6>Ticket Name</h6>
                    {!! Form::text('name', null, array('placeholder'=>'Ticket Name', 'class' =>'form-control')) !!}
                </div>
                <div class="text-center">
                    <h6>Ticket Description</h6>
                    {!! Form::textarea('description', null, array('placeholder'=>'Ticket Description', 'class' =>'form-control')) !!}
                </div>
                <div class="text-center form-group">
                    <h6>Department</h6>
                    {!! Form::select('department_id', $data['departments'] , $data['ticket']->department->id  , array('class' =>'form-control')) !!}
                </div>
                <div class="text-center">
                    {!! Form::submit('Edit Ticket', array('class' => 'btn brownBtn')) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    @endif
@endsection