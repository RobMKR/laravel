@extends('layouts.app')

@section('title', 'JTI Armenia - Slips')

@section('content')
<div class="container">

    <div id="error" class="w w-100" style="display:none;"></div>
    <div id="success" class="w w-100" style="display:none;"></div>
    <div class="row">
        {!! Form::open(['method' => 'post', 'id' => 'takeGiftForm', 'files' => true]) !!}    
            <div class="col-md-10 col-md-offset-1">
                    
                <div class="text-center w w-50">
                    {!! Form::text('phone','', array('class' =>'form-control', 'placeholder' => 'Հեռախոսահամար')) !!}
                </div>

                <div class="text-center w w-50">
                    {!! Form::submit('ՓՆՏՐԵԼ ՄԱՍՆԱԿՑԻՆ', array('class' => 'btn btn-primary w-100')) !!}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
    <div class="row">
        {!! Form::open(['method' => 'post', 'id' => 'giveGiftForm']) !!}    
            <div class="col-md-10 col-md-offset-1 clientFormBody">
                
            </div>

            <div class="col-sm-8 col-sm-offset-2 clientGiftsBody">
                
            </div>

            <div class="text-center w w-100" style="display:none;">
                {!! Form::submit('ԱՎԵԼԱՑՆԵԼ ՆՎԵՐԸ ՄԱՍՆԱԿՑԻՆ', array('class' => 'btn btn-primary w-100')) !!}
            </div>            
        {!! Form::close() !!}
    </div>

</div>
<script type="text/javascript" src="/js/gift.js"></script>
@endsection
