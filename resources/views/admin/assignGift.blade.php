@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Assign Gifts')

@section('content')
    @parent
    <div class="container">
        <h3> Add Gifts To shop </h3>
        <hr>
        {!! Form::open(['method' => 'post']) !!}
        <div class="row">
            
            <div class="text-center col-md-12">
                {!! Form::select('shop_id',$data['shops'],Request::get('s'), array('class' =>'form-control', 'placeholder' => 'Select Shop')) !!}
            </div>
            <div class="text-center col-md-12">
                {!! Form::select('gift_id',$data['gifts'],Request::get('g'), array('class' =>'form-control', 'placeholder' => 'Select Gift')) !!}
            </div>
            <div class="text-center col-md-12">
                {!! Form::text('count', isset($data['counts'][Request::get('s')][Request::get('g')]) ? $data['counts'][Request::get('s')][Request::get('g')] : null , array('class' =>'form-control' , 'placeholder' => 'Select Gift Count')) !!}
            </div>
            <div class="text-center col-md-12">
                {!! Form::submit('Save', array('class' => 'btn btn-primary')) !!}
            </div>
            
        </div>
        {!! Form::close() !!}
    </div>
    
    <script type="text/javascript">  
        $(document).ready(function(){
            var count = '<?php echo json_encode($data["counts"]); ?>';
            count = JSON.parse(count);
            
            $('select').change(function(){
                var shop = $('select[name="shop_id"]').val();
                var gift = $('select[name="gift_id"]').val();

                if(shop == "" || gift == ""){
                    $('input[name="count"]').val('');
                    return false;
                }

                if(count[shop] != undefined && count[shop][gift] != undefined){
                    $('input[name="count"]').val(count[shop][gift]);
                    return false;
                }
            });
        });
    </script>

@endsection