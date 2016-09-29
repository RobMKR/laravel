@extends('layouts.app')

@section('title', 'Laravel - Manage Department')

@section('content')
    <div class="container">
        <h3> Pending Tickets </h3>
        <hr>
        <div class="row">
            @if(isset($data['tickets']['pending']))
                {{var_dump($data['tickets']['pending'])}}
            @else
                <p>There are no pending tickets.</p>
            @endif
        </div>
        <h3> Ongoing Tickets </h3>
        <hr>
        <div class="row">
            @if(isset($data['tickets']['ongoing']))
                {{var_dump($data['tickets']['ongoing'])}}
            @else
                <p>There are no ongoing tickets.</p>
            @endif
        </div>
        <h3> Completed Tickets </h3>
        <hr>
        <div class="row">
            @if(isset($data['tickets']['completed']))
                {{var_dump($data['tickets']['completed'])}}
            @else
                <p>There are no completed tickets.</p>
            @endif
        </div>
    </div>
@endsection
