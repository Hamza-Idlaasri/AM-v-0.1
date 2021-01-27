<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Alarm Manager</title>

    <link rel="stylesheet" type="text/css" href="{{ asset('css/menu.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/common.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" />

    <script type="text/javascript" src="{{asset ('js/jquery-1.7.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset ('js/menu.js')}}"></script>

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
            @yield('content')
        </div>
    </div>
    
    
</body>
</html>