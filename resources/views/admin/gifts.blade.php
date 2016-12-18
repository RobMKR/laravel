@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Gifts')

@section('content')
    @parent
    <div class="container">
        <div class="header clearfix">
            <div class="page-title fl"> 
                Showning All Gifts That You can Edit/Delete 
            </div>
            <div class="add-btn fr">
                <a href="{{url('/admin/addGift')}}" class="btn btn-default">Add New Gift</a>
            </div>  
        </div>
        @if(!empty($gifts))
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Picture</th>
                    <th>Icon (FontAwesome)</th>
                    <th>Created</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($gifts as $_key => $gift)
                        <tr>
                            <td>{{$_key + 1}}</td>
                            <td>{{$gift->name}}</td>
                            <td>{{ucfirst($gift->icon_type)}}</td>
                            <td class="gift-img">
                                <img src="{{$gift->icon_url}}">
                            </td>
                            <td class="gift-ico"><i class="fa {{$gift->icon_class}}"></i></td>
                            <td>{{$gift->created_at}}</td>
                            <td><a href="{{url('/admin/editGift/' . Hashids::encode($gift->id))}}" class="glyphicon glyphicon-pencil"></a></td>
                            <td><a href="{{url('/admin/deleteGift/' . Hashids::encode($gift->id))}}" class="glyphicon glyphicon-remove"></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    
@endsection