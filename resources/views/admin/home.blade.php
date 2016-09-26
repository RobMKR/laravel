@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Home')

@section('content')
    @parent
    <div class="container">
        <h3> Showning All Users That You can Edit/Delete </h3>
        @if(!empty($users))
            <ul class="list-group">
            @foreach($users as $user)
                <li class="userInfo list-group-item">
                    <span>
                        <strong>Name: </strong>{{$user->name}}
                        <strong>Email: </strong>{{$user->email}}
                        <strong>Role: </strong>{{ucwords($user->role)}}
                        <strong>Created: </strong>{{$user->created_at}}
                        <strong>Updated: </strong>{{$user->updated_at}}

                    </span>
                    <a href="{{url('/admin/editUser/' . Hashids::encode($user->id))}}" class="glyphicon glyphicon-pencil"></a>
                    <a href="{{url('/admin/deleteUser/' . Hashids::encode($user->id))}}" class="glyphicon glyphicon-remove"></a>
                </li>
            @endforeach
            </ul>
        @endif
    </div>
    
@endsection