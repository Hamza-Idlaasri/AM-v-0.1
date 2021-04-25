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
            margin: 0
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
        
           
                <form action="{{ route('register') }}" method="post" class="p-3">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="name" class="form-control @error('name') border-danger @enderror" placeholder="Username" value="{{ old('name') }}">
                        @error('name')
                            <div class="text-danger">
                                    {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
            
                    <div class="form-group">
                        <input type="email" name="email" class="form-control @error('email') border-danger @enderror" placeholder="Email"  value="{{ old('email') }}">
                        @error('email')
                            <div class="text-danger">
                                    {{ $message }}
                            </div>
                        @enderror
                    </div>
                   

                    <div class="form-group">
                        <input type="password" name="password" class="form-control @error('password') border-danger @enderror" placeholder="Password">
                        @error('password')
                        <div class="text-danger">
                                {{ $message }}
                        </div>
                        @enderror   
                    </div>
                    

                    <div class="form-group">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirme Password">
                    </div>
                    
                    <div>
                        <button type="submit" class="btn btn-primary w-100 font-weight-bold">Register</button>
                    </div>
                </form>
                {{-- <hr>
                <div class="text-center">
                    <p>j'ai deja un compte?<a href="{{ route('login') }}">  Login</a></p>
                </div> --}}
            </div>
        </div>
    </div>
</body>
</html>