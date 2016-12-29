@extends('layouts.admin_layout')

@section('title', 'Admin Panel - Available Gifts')

@section('content')
    @parent
    <div class="container">
        <div class="header clearfix">
            <div class="page-title fl"> 
                Showing All consumers that have reserved or taken gifts
            </div>
        </div>
        @if(!empty($data))
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Phone</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Birth Date</th>
                    <th>Passport</th>
                    <th>Week</th>
                    <th>Shop</th>
                    <th>Gifts</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($data as $_key => $client)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{$client['phone']}}</td>
                            <td>{{$client['name']}}</td>
                            <td>{{$client['surname']}}</td>
                            <td>{{$client['birth_date']}}</td>
                            <td>{{$client['passport']}}</td>
                            <td>{{$client['week_name']}}</td>
                            <td>{{$client['shop_name']}}</td>
                            <td class="gift" data-id="{{$_key}}" data-name="{{$client['name'] . ' ' . $client['surname']}}">
                                <a href="#" class="btn">View</a>
                            </td>                            
                        </tr>
                    @endforeach
                </tbody>
            </table>

        @endif
    </div>
    <div id="viewGifts"  class="mgnPopup mfp-hide">
        <div class="mgnHeader">
            <div class="clientName">
                
            </div>
        </div>
        <hr>
        <div class="clientGifts">
            <div class="taken w w-50">
                <h4>Taken Gifts</h4>
                <div class="body"></div>
            </div>
            <div class="reserved w w-50">
                <h4>Reserved Gifts</h4>
                <div class="body"></div>
            </div>
        </div>
        <div class="btn-group">
            <button class="closeBtn btn btn-default">Close</button>
        </div>
    </div>

    <script type="text/javascript">
        $(function(){
            $('.gift').click(function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                var name =  $(this).attr('data-name');

                $.ajax({
                    url : '/admin/getGiftsAjax',
                    type : 'POST',
                    data : {
                        _token : Laravel.csrfToken,
                        id : id
                    },
                    success : function(rsp){
                        var html = '';

                        $('.clientName').html(name);
                        $('.taken .body').empty();
                        $('.reserved .body').empty();

                        $.each(rsp.data.taken_gifts, function(){
                            $('.taken .body').append('<div class="text-row">' + this.GiftName + '</div>');
                        });

                        $.each(rsp.data.reserved_gifts, function(){
                            $('.reserved .body').append('<div class="text-row">' + this.GiftName + '</div>');
                        });

                        $.magnificPopup.open({ items : { src : '#viewGifts' }});
                    }

                });
            });

            $('.closeBtn').click(function(){
                $.magnificPopup.close();
            });
        });
    </script>
@endsection