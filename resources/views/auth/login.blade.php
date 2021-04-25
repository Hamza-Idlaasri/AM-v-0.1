<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="{{ asset('images/interface/Logo.png') }}" type="image/icon type">
    <title>Alarm Manager</title>

    <style>
        body
        {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        #header {
            background-color: rgb(0, 151, 211);

        }
    </style>
</head>
<body>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4 bg-white shadow rounded p-0">

            <div class="text-center" id="header">
                <img src="{{ asset('images/interface/AlarmManager.png') }}" alt="">
            </div>
        
                <form action="{{ route('login') }}" method="post" class="p-3">
                    @csrf

                    @if (session('status'))
                        <div class="alert alert-danger text-center">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="form-group">
                        <input type="text" name="name" class="form-control @error('name') border-danger @enderror" placeholder="Username">
                    </div>
                    @error('name')
                        <div class="text-danger">
                                {{ $message }}
                        </div>
                    @enderror
            
                    <div class="form-group">
                        <input type="password" name="password" class="form-control @error('password') border-danger @enderror" placeholder="Password">
                    </div>
                    @error('password')
                        <div class="text-danger">
                                {{ $message }}
                        </div>
                    @enderror

                    <div class="clear-fix">
                        <div class="form-group float-left">
                            <input type="checkbox" tabindex="3" class="" name="remember" id="remember">
                            <label for="remember"> Remember Me</label>
                        </div>

                        {{-- <div class="form-group float-right">  
                            <a href="" tabindex="5" class="forgot-password">Mot de pass oublie?</a>
                        </div> --}}
                    </div>
                    

                    <div>
                        <button type="submit" class="btn btn-primary w-100"><strong>Login</strong></button>
                    </div>

                    
                </form>
                {{-- <hr>
                <div class="text-center">
                    <p>Vous n'avez pas un compte?<a href="{{ route('register') }}">  Inscrivez-vous</a></p>
                </div> --}}
            </div>
        </div>
    </div>
</body>
</html>