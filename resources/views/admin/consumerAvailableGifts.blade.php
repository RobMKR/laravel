@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Available Gifts')

@section('content')
    @parent
    <div class="container">
        <div class="header clearfix">
            <div class="page-title fl"> 
                Showing All consumers that have gifts
            </div> 
            <div class="fr sms">
                <select class="form-control weeks" style="margin-bottom: 5px;">
                    <option value="" selected> Select Week </option>
                    @foreach($data['weeks'] as $_week)
                        <option value="{{$_week->id}}">
                            {{date('d F Y' , strtotime($_week->start))}} to {{date('d F Y' , strtotime($_week->end) + 1)}}
                        </option>
                    @endforeach  
                </select>
                
                <a href="/admin/sendSmsSandbox" class="btn btn-default">Send SMS [sendbox]</a>
                <a href="/admin/sendSmsReal" class="btn btn-default">Send SMS</a>
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
                        <tr data-week="{{$gift->week_id}}">
                            <td>{{ (($data['gifts']->currentPage() - 1 ) * $data['gifts']->perPage() ) + $loop->iteration }}</td>
                            <td>{{$gift->name}}</td>
                            <td>{{$gift->surname}}</td>
                            <td>{{date('d F Y' , strtotime($gift->start))}}</td>
                            <td>{{date('d F Y' , strtotime($gift->end) + 1)}}</td>
                            <td>{{$gift->short_name}}</td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $data['gifts']->links() }}

        @endif
    </div>

    <div id="available-gifts-popup" class="mgnPopup mfp-hide">
        <div class="container" style="width: 100%;">
            <div class="inline-loader">

            </div>
        </div>
        <div class="btn-group">
            <a id="send" class="btn-default btn">Send</a>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            var week;
            var url;

            $('.weeks').on('change', function(){
                week = $(this).val();
                $('tr[data-week]').css('background-color', '#fff');
                $('tr[data-week="' + $(this).val() + '"]').css('background-color', '#abd9ab');
            });

            $('.sms a').click(function(e){
                url = $(this).attr('href');
                e.preventDefault();

                if(week == ''){
                    alert('Select Week');
                    return false;
                }

                $.magnificPopup.open({
                    items : {
                        src : '#available-gifts-popup'
                    },
                    type : 'inline'
                });

                $.ajax({
                    url : '/admin/getAvailableGiftShops',
                    data : {
                        week : week
                    },
                    success : function (response) {
                        if(response.success){
                            drawResults(response.shops);
                            $('#send').attr('href', url + '?week=' + week);
                        }
                    }
                });

                return false;
            });

            $('.sms a').click(function(e){
//                e.preventDefault();
//                var week = $('.weeks').val()
//                if(week == ''){
//                    alert('Select Week');
//                    return false;
//                }
//
//                var href = $(this).attr('href') + '?week=' + week;
//                window.location = href;
            });

            function drawResults(shops) {
                var container = $('#available-gifts-popup .container');
                var html = '<table class="table table-bordered">';

                for(var shop in shops){
                    html += '<tr>';
                    html += '<td>';
                    html += shop;
                    html += '</td>';
                    html += '<td>';
                    console.log(shops[shop]);
                        for(var gift in shops[shop]['counts']){
                            html += '<span class="sep-row">';
                            html += gift + ' - ' + shops[shop]['counts'][gift];
                            html += '</span>';
                        }
                    html += '</td>';
                    html += '</tr>';
                }

                html += '</table>';

                container.html(html);
            }


        });
    </script>
@endsection