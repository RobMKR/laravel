@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Edit Gift')

@section('content')
    @parent
    @if(!empty($data))
        <div class="container">
            <h3> Edit Gift </h3>
            <hr>
            <div class="row">
                {!! Form::model($data, ['action' => ['GiftController@edit', Hashids::encode($data->id)], 'files' => true]) !!}
                <div class="text-center">
                    <h6>Gift Name</h6>
                    {!! Form::text('name',null, array('class' =>'form-control')) !!}
                </div>
                <div class="text-center">
                    <h6>Gift Type</h6>

                    <span>Image (Size - 64x64)</span>
                    {!! Form::radio('icon_type','url', null , array('class' =>'radio-control')) !!}
                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span>Font Awesome Class</span>
                    {!! Form::radio('icon_type','class', null, array('class' =>'radio-control')) !!}
                    <br/><br/>
                </div>

                <div class="text-center">
                    {!! Form::file('icon_url', array('class' =>'form-control')) !!}
                </div>

                <div class="text-center">
                    <h6>Gift class name</h6>
                    {!! Form::text('icon_class',null, array('class' =>'form-control')) !!}
                </div>
                <br>
                <div class="text-center">
                    {!! Form::submit('Save', array('class' => 'btn btn-primary')) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    @endif
@endsection