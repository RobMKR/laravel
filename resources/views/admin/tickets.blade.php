@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Tickets')

@section('content')
    @parent
    <div class="container">
        <h3> Tickets </h3>
        <hr>
        <div class="row">
            @if(isset($data['tickets']))
                <div class="tickets-pending">
                    <h4> Pending </h4>
                    <ul>
                        @foreach($data['tickets'] as $_ticket)
                            <li class="list-group-item">
                                <span>
                                    <strong>Name: </strong>{{$_ticket->name}} |
                                    <strong>Department: </strong>{{$_ticket->department->name}} |
                                    <strong>Added By: </strong>{{$_ticket->user->name}}
                                </span>
                                <a href="{{url('/admin/tickets/manage/' . Hashids::encode($_ticket->id))}}" class="fr"> Manage </a>
                            </li>
                        @endforeach
                        <li class="list-group-item">{{$data['tickets']->links()}}</li>
                    </ul>
                </div>
            @endif
        </div>
    </div>
@endsection