<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="/css/main.css" rel="stylesheet">
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="/js/jquery_2.min.js"></script>
    <script src="/js/notification.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>

    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <nav class="navbar navbar-default navbar-static-top nav-admin">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/admin') }}">
                    Admin Panel
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->



                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{url('/admin/tickets')}}" role="button">Manage Tickets</a></li>
                    <li><a href="{{url('/admin/departments')}}" role="button">Manage Departments</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/home') }}">Switch to Site</a></li>
                            <li><a href="{{ url('/admin/editAccount') }}">Edit Account</a></li>
                            <li class="divider"></li>
                            <li>
                                <a href="{{ url('/logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success')}}</div>
    @endif

    @yield('content')
    <div class="notification-bar">
        <div class="panel panel-default">
            <div class="panel-heading">Notification Bar</div>
            <div class="panel-body">
                <div id="messages"></div>
                <div>
                    <form action="sendmessage" method="POST">
                        <input type="hidden" name="_token" value="I0PazTS85uo5WEZDKyqiBjtrrfWLcg1Hi8MaTxo0">
                        <input type="hidden" name="user" value="admin">
                        <textarea class="form-control msg"></textarea>
                        <br>
                        <input type="button" value="Send" class="btn btn-success send-msg">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        var user_hashed = '{{sha1(Auth::user()->id)}}';
        var socket = io.connect('http://localhost:8890', {
            query: '{{hash_hmac('SHA1', 'user', 'A2888mTnk874MB')}}={{hash_hmac('SHA1', Auth::user()->id, 'A2888mTnk874MB')}}&{{hash_hmac('SHA1', 'role', 'A2888mTnk874MB')}}={{hash_hmac('SHA1', Auth::user()->role, 'A2888mTnk874MB')}}'
        });
        /* Get Message From Socket Server */
        socket.on('message', function (data) {
            data = jQuery.parseJSON(data);
            if(data.user_hashed !== user_hashed){
                $( "#messages" ).append( "<strong>System:</strong><p>"+data.message+"</p>" );
                notifyMe({user: data.user , msg: data.message});
                Panel.open();
            }
        });

        /* Send Messages To Socket Server */
        $(".send-msg").click(function(e){
            e.preventDefault();
            var token = $("input[name='_token']").val();
            var user = $("input[name='user']").val();
            var msg = $(".msg").val();
            if(msg != ''){
                $.ajax({
                    type: "POST",
                    url: '{!! URL::to("/sendmessage") !!}',
                    dataType: "json",
                    data: {'_token':token,'message':msg,'user':user},
                    success:function(data){
                        $(".msg").val('');
                    }
                });
            }else{
                alert("Please Add Message.");
            }
        })
    </script>
    <script src="/js/app.js"></script>
    <script src="/js/msg.js"></script>
</body>
</html>
