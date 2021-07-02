<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="refresh" content="15">
    <link rel="icon" href="{{ asset('images/interface/Logo.png') }}" type="image/icon type">
    <title>Alarm Manager</title>

    <link rel="stylesheet" type="text/css" href="{{ asset('css/sidebar.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/common.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" />

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    <script type="text/javascript" src="{{asset ('js/jquery-1.7.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset ('js/sidebar.js')}}"></script>
    <script type="text/javascript" src="{{asset ('js/chart.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/vis.js') }}"></script>
    

</head>

<body>
    
    <div class="grid-container">
        <div class="top">
            @include('inc.top')
        </div>
       
        <div class="sidebar">
            @include('inc.sidebar')
        </div>

        <div class="main">

            {{-- main content --}}

            @yield('content')

            {{-- Toggle butoon --}}

            <div class="toggle-off">
                <button class="tg-btn-off"><i class="fa fa-bars"></i></button>
            </div>

        </div>
    </div>
    
    <script type="text/javascript" src="{{ asset('js/toggle.js') }}"></script>
    
</body>
</html>