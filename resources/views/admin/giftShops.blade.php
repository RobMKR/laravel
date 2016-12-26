@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Gifts in shops')

@section('content')
    @parent
    <div class="container">
        <div class="header clearfix">
            <div class="page-title fl"> 
                Gift counts in shops 
            </div>
        </div>
        @if(!empty($data['shops']))
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Shop Name</th>
                    @if(!empty($data['gifts']))
                        @foreach($data['gifts'] as $_gift)
                            <th data-id="{{$_gift['id']}}">{{$_gift['name']}}</th>
                        @endforeach
                    @endif
                </tr>
                </thead>
                <tbody>
                    @if(!empty($data['shops']))
                        @foreach($data['shops'] as $_shop)
                        <tr>
                            <td>{{$_shop['short_name']}}</td>
                            @foreach($data['gifts'] as $_gift)
                                <td>
                                    <a href="{{url('admin/assignGift?s=' . $_shop['id'] . '&g=' . $_gift['id'])}}">
                                        {{isset($data['counts'][$_shop['id']][$_gift['id']]) ? $data['counts'][$_shop['id']][$_gift['id']] : 0}}
                                    </a>
                                </td>                            
                            @endforeach
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        @endif
    </div>
    
@endsection