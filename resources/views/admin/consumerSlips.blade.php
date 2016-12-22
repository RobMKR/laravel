@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Slips')

@section('content')
    @parent
    <div class="container">
        <div class="header clearfix">
            <div class="page-title fl"> 
                Showing All consumers
            </div> 
        </div>
        @if(!$data['slips']->isEmpty())
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Week Start</th>
                    <th>Week End</th>
                    <th>Count</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($data['slips'] as $_key => $slip)
                        <tr>
                            <td>{{$slip->id}}</td>
                            <td>{{$slip->name}}</td>
                            <td>{{$slip->surname}}</td>
                            <td>{{$slip->start}}</td>
                            <td>{{$slip->end}}</td>
                            <td>{{$slip->slip_count}}</td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $data['slips']->links() }}

        @endif
    </div>
    
@endsection