@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Overall Counts')

@section('content')
    @parent
    <div class="container">
        <div class="header clearfix">
            <div class="page-title fl"> 
                Showning Overall client counts in weeks
            </div>
        </div>
        <hr>
        @if(!empty($data))
            @foreach($data as $_key1 => $_week)
                <div class='overall-table'>
                    <div class='table-header'>Week: {{$_week['week_info']['number']}} ({{$_week['week_info']['start']}} - {{$_week['week_info']['end']}})</div>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Shop</th>
                            <th>Reserved Gift</th>
                            <th>Taken Gift</th>
                            <th>Ready to Get Gift</th>
                            <th>Not Accepted</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($_week['shops'] as $_key2 => $_shop)
                                <tr>
                                    <td>
                                        {{$_shop['shop_info']}}
                                    </td>
                                    @foreach($_shop['counts'] as $_count)
                                        <td>{{ count($_count) }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
            </ul>
        @endif
    </div>
    
@endsection