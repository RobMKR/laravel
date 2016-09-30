@extends('layouts.app')

@section('title', 'Laravel - Manage Tickets')

@section('content')
    <div class="container">
        <h3> Accepted Tickets </h3>
        <hr>
        <div class="row">
            @if(isset($data['tickets']))
                <ul class="list-group">
                @foreach($data['tickets'] as $_ticket)
                    <li class="userInfo list-group-item">
                        <span>
                            <strong>Name: </strong>{{$_ticket->name}}
                            <strong>Added By: </strong>{{$_ticket->user->name}}
                            <strong>Accept Date: </strong>{{$_ticket->updated_at}}
                        </span>
                        <a href="#assign-user-to-ticket" class="fr magnificPopup" data-id="{{$_ticket->id}}">Assign To Staff</a>
                    </li>
                @endforeach
                </ul>
            @else
                <p>There are no accepted tickets.</p>
            @endif
        </div>
    </div>

    <div id="assign-user-to-ticket" class="mgnPopup mfp-hide">
        <div class="form-group">
            <h3>Select Staff To Assign To Ticket</h3>
            <hr>
            {{ Form::select('role', $data['staff'] ,null, array('placeholder' => 'Select Staff To Assign To Ticket', 'class' =>'form-control')) }}
            <div class="btn-group">
                <button class="btn popup-submit btn-default text-center">Assign</button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('.magnificPopup').magnificPopup({type:'inline'});
            $('.magnificPopup').click(function(){
                $('.popup-submit').attr('data-id', $(this).attr('data-id'));
            });
            $(document).on('click', '.popup-submit', function(){
                var value = $(this).parents('.mgnPopup').find('select').val();
                var ticket = $(this).attr('data-id');
                var crsf = Laravel.csrfToken;
                console.log(value);
                if(value == ''){
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: '{!! URL::to("/departments/tickets/assign") !!}',
                    dataType: "json",
                    data: {'_token':crsf,'user':value,'ticket':ticket},
                    success:function(data){
                        if(data.status === 'ok'){
                            $('a.magnificPopup[data-id="'+ticket+'"]').parents('li').remove();
                            $.magnificPopup.close();
                        }
                    }
                });
            });
        });
    </script>
@endsection
