@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Shops')

@section('content')
    @parent
    <div class="container">
        <div class="header clearfix">
            <div class="page-title fl"> 
                Showning All Shops That You can Edit/Delete 
            </div>
            <div class="add-btn fr">
                <a href="{{url('/admin/addShop')}}" class="btn btn-default">Add New Shop</a>
            </div>  
        </div>
        @if(!empty($shops))
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Short Name</th>
                    <th>Full Name</th>
                    <th>Created</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($shops as $_key => $shop)
                        <tr>
                            <td>{{$_key + 1}}</td>
                            <td>{{$shop->short_name}}</td>
                            <td>{{$shop->full_name}}</td>
                            <td>{{$shop->created_at}}</td>
                            <td><a href="{{url('/admin/editShop/' . Hashids::encode($shop->id))}}" class="glyphicon glyphicon-pencil"></a></td>
                            <td><a href="{{url('/admin/deleteShop/' . Hashids::encode($shop->id))}}" class="glyphicon glyphicon-remove"></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    
@endsection