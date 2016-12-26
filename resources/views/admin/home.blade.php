@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Home')

@section('content')
    @parent
    <div class="container">
        <div class="header clearfix">
            <div class="page-title fl"> 
                Showning All Users That You can Edit/Delete 
            </div>
            <div class="add-btn fr">
                <a href="{{url('/admin/addUser')}}" class="btn btn-default">Add New User</a>
            </div>  
        </div>
        <hr>
        @if(!empty($users))

            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($users as $_key => $user)
                        <tr>
                            <td>{{$_key + 1}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{($user->role == 'admin') ? 'Gift assistant' : 'Slip assistant'}}</td>
                            <td>{{$user->created_at}}</td>
                            <td><a href="{{url('/admin/editUser/' . Hashids::encode($user->id))}}" class="glyphicon glyphicon-pencil"></a></td>
                            <td><a href="{{url('/admin/deleteUser/' . Hashids::encode($user->id))}}" class="glyphicon glyphicon-remove"></a></td>                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </ul>
        @endif
    </div>
    
@endsection