@extends('layouts.app')

@section('title', 'Laravel - Departments')

@section('content')
    <div class="container">
        <h3> Tickets </h3>
        <div class="row">
            <div class="tickets">
                @if(isset($data['tickets']))
                    <ul>
                        @foreach($data['tickets'] as $_ticket)
                            <li class="ticketInfo list-group-item text-center">
                                <span>
                                    <strong>Name: </strong>{{$_ticket->name}}
                                    <strong>Description: </strong>{{$_ticket->description}}
                                    <strong>Department: </strong>{{$_ticket->department->name}}
                                    <strong>Status: </strong>{{ucwords($_ticket->status)}}
                                    <strong>Added By: </strong>{{$_ticket->user->name}}
                                </span>
                                @if(Auth::user()->getLevel() === 3 || Auth::user()->id === $_ticket->user_id)
                                    <a href="{{url('/tickets/edit/' . Hashids::encode($_ticket->id))}}" class="glyphicon glyphicon-pencil"></a>
                                    <a href="{{url('/tickets/delete/' . Hashids::encode($_ticket->id))}}" class="glyphicon glyphicon-remove"></a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <hr>
            <a href="{{url('/tickets/add')}}" class="btn btn-default"> Add Ticket</a>
        </div>
    </div>
@endsection
