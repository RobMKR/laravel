@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Available Gifts')

@section('content')
    @parent
    <div class="container">
        <div class="header clearfix">
            <div class="page-title fl"> 
                Showing All consumers that have gifts
            </div> 
        </div>
        @if(!$data['gifts']->isEmpty())
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Week Start</th>
                    <th>Week End</th>
                    <th>Shop</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($data['gifts'] as $_key => $gift)
                        <tr>
                            <td>{{$gift->id}}</td>
                            <td>{{$gift->name}}</td>
                            <td>{{$gift->surname}}</td>
                            <td>{{$gift->start}}</td>
                            <td>{{$gift->end}}</td>
                            <td>{{$gift->short_name}}</td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $data['gifts']->links() }}

        @endif
    </div>
    
@endsection