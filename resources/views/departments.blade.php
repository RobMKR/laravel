@extends('layouts.app')

@section('title', 'Laravel - Departments')

@section('content')
    <div class="container">
        <h3> Departments </h3>
        <div class="row">
            <div class="departments">
                @if(isset($data['departments']))
                    <ul>
                        @foreach($data['departments'] as $_department)
                            <li class="departmentInfo list-group-item text-center">
                                <span>
                                    <strong>Name: </strong>{{$_department->name}}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection
