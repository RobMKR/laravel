@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Edit Shop')

@section('content')
    @parent
    @if(!empty($data))
        <div class="container">
            <h3> Edit Shop </h3>
            <hr>
            <div class="row">
                {!! Form::model($data, ['action' => ['ShopController@edit', Hashids::encode($data->id)]]) !!}
                <div class="text-center">
                    <h6>Short Name</h6>
                    {!! Form::text('short_name', null, array( 'class' =>'form-control')) !!}
                </div>
                <div class="text-center">
                    <h6>Full Name</h6>
                    {!! Form::text('full_name', null , array('class' =>'form-control')) !!}
                </div>
                </div>
                <div class="text-center">
                    {!! Form::submit('Save', array('class' => 'btn btn-primary')) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    @endif
@endsection