@extends('layouts.app')

@section('title', 'Laravel - Department Staff')

@section('content')
    <div class="container">
        <h3> Department Staff </h3>
        <hr>
        <div class="row">
            @if(isset($data['staff']))
                {{var_dump($data['staff'])}}
            @else
                <p>There are no staff in department.</p>
            @endif
        </div>
    </div>
@endsection
