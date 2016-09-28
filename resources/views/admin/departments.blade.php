@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Departments')

@section('content')
    @parent
    <div class="container">
        <h3> Departments </h3>
        <div class="row">
            <div class="departments">
                @if(isset($data['departments']))
                    <ul>
                        @foreach($data['departments'] as $_department)
                            <li class="departmentInfo list-group-item text-center">
                                <span>
                                    <strong>Name: </strong>{{$_department->name}} |
                                    <strong>Owner: </strong>{{$_department->owner->name}} |
                                    <strong>Created: </strong>{{$_department->created_at}} |
                                    <strong>Created: </strong>{{$_department->updated_at}}
                                </span>
                                <a href="{{url('/admin/editDepartment/' . Hashids::encode($_department->id))}}" class="glyphicon glyphicon-pencil"></a>
                                <a href="{{url('/admin/deleteDepartment/' . Hashids::encode($_department->id))}}" class="glyphicon glyphicon-remove"></a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <hr>
            <a href="{{url('/admin/addDepartment')}}" class="btn btn-default"> Add Department</a>
        </div>
    </div>
@endsection