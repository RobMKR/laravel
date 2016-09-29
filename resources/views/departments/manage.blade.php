@extends('layouts.app')

@section('title', 'Laravel - Manage Department')

@section('content')
    <div class="container">
        <h3> Manage Department </h3>
        <hr>
        {{var_dump($data['department'])}}
    </div>
@endsection
