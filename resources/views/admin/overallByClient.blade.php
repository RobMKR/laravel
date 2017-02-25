@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Overall By Consumer')

@section('content')
    @parent
    <div class="container">
        <div class="header clearfix">
            <div class="page-title fl"> 
                Showning Client statistics
            </div>
        </div>
        <hr>
        @if(!empty($data))
            <table class="table table-bordered" id='datatable'>
                <thead>
                <tr>
                    <th>Outlet</th>
                    <th>Phone</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Birth Date</th>
                    <th>Passport</th>

                    @foreach($data['weeks'] as $_week)
                        <th>Week {{$_week['num']}}</th>
                    @endforeach 

                    @foreach($data['gifts'] as $_gift)
                        <th>{{$_gift->name}}</th>
                    @endforeach

                </tr>
                </thead>
                <tbody>
                    @foreach($data['clients'] as $_client)
                        <tr>
                            <td>{{$_client['shop']}}</td>
                            <td>{{$_client['client']['phone']}}</td>
                            <td>{{$_client['client']['name']}}</td>
                            <td>{{$_client['client']['surname']}}</td>
                            <td>{{$_client['client']['birth_date']}}</td>
                            <td>{{$_client['client']['passport']}}</td>

                            @foreach($data['weeks'] as $_id => $_week)
                                <td>{{ isset($_client['weeks'][$_id]) ? $_client['weeks'][$_id]['slip_count'] : 0 }}</td>
                            @endforeach 

                            @foreach($data['gifts'] as $_gift)
                                <td>{{ isset($_client['gifts'][$_gift->id]) ? $_client['gifts'][$_gift->id] : '' }}</td>
                            @endforeach
                        </tr>
                    @endforeach                  
                </tbody>
            </table>
        @endif
    </div>
    
    <script type="text/javascript">
        $(document).ready(function(){
            $('#datatable').DataTable({
                "scrollX": true,
                dom: 'Bfrtip',
                pageLength: 200,
                buttons: [
                    'excelHtml5',
                    'csvHtml5',
                ]
            });
        });
    </script>

@endsection


