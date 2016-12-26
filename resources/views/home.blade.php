@extends('layouts.app')

@section('title', 'JTI Armenia - Slips')

@section('content')
<div class="container">
    <div class="row">
        

        </div>
        {!! Form::open(['method' => 'post', 'id' => 'addSlipForm']) !!}    
            <div class="col-md-10 col-md-offset-1">
                <div id="error" class="w w-100" style="display:none;"></div>
                <div id="success" class="w w-100" style="display:none;"></div>
                <div class="text-center w w-100 form-group">
                    {!! Form::select('shop_id', $shops, null, ['class' => 'form-control', 'placeholder' => 'Ընտրեք վաճառակետը']) !!}
                </div>
                    
                <div class="text-center w w-50">
                    {!! Form::text('phone','', array('class' =>'form-control', 'placeholder' => 'Հեռախոսահամար')) !!}
                </div>
                <div class="text-center form-group w w-25">
                    {!! Form::text('date','', array( 'id' => 'datepicker', 'class' =>'form-control', 'placeholder' => 'Ամսաթիվ')) !!}
                </div>

                <div class="text-center form-group w w-10">
                    <select class="form-control p2" name="hour">
                        <option value="00">00</option>
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10" selected>10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                    </select>
                </div>
                <div class="text-center form-group w w-10">
                    <select class="form-control p2" name="min">
                        @for($i = 0; $i < 60; $i++)
                            <option value="{{(strlen($i) == 1) ? '0' . $i : $i}}">{{(strlen($i) == 1) ? '0' . $i : $i}}</option>
                        @endfor
                    </select>
                </div>
                <input type="hidden" value="find" name="type"/>
                <div class="form-group w w-50">
                    <div class="radio-btns clearfix">
                        <div class="fl w-100" style="height: 100%;padding: 6px 15px;">
                            <span>Կտրոնների քանակ</span>
                        </div>
                    </div>  
                </div>

                <div class="form-group w w-50">
                    <select class="form-control p3" name="count">
                        <option value="1" selected>1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                    </select>
                </div>

                <div class="name-surname-block">
                    
                </div>
                <div class="text-center w w-100">
                    {!! Form::submit('Ավելացնել կտրոնը', array('class' => 'btn btn-primary w-100')) !!}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
