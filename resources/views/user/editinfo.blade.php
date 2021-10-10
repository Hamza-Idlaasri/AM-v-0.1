@extends('layouts.app')

@section('content')

<style>
    .check {
        position: relative;
        height: 15px;
    }

    input#rail[type="checkbox"] {
        width: 35px;
        height: 15px;
        -webkit-appearance: none;
        background: #c6c6c6;
        outline: none;
        border-radius: 30px;
        box-shadow: inset 0 0 5px rgb(0, 0, 0, 0.2);
        transition: .5s;
        cursor: pointer;
    }

    input#rail:checked[type="checkbox"] {
        background: rgb(0, 151, 211);
    }

    input#rail[type="checkbox"]:before {
        content: '';
        position: absolute;
        top: -2px;
        left: 0;
        width: 20px;
        height: 20px;
        border: 1px solid rgb(0, 0, 0, 0.2);
        border-radius: 30px;
        background: white;
        box-shadow: 0 2px 5px rgb(0, 0, 0, 0.2);
        transition: .5s;
    }

    input#rail:checked[type="checkbox"]:before {
        left: 20px;
    }

    input#rail:hover[type="checkbox"]:before {
        background: rgb(240, 240, 240);
    }

    .unity{
            background: rgb(238, 238, 238);
            color: black;
            display: flex;
            justify-content: center;
            align-items: center;
            padding:0 1px;
            border-radius: 5px 0 0 5px;
            width: 20%;
            border: 1px solid #ced4da;
            border-right: 0px;
        }
    .p-unity
    {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        width: 80%;
    }
</style>

<div class="container my-5 w-25 bg-white shadow p-3">

    @if (session('status'))
        <div class="alert alert-danger text-center">
            {{ session('status') }}
        </div>
    @endif

   <form action="{{ route('changeInfo') }}" method="post">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="username" class="font-weight-bold">Username :</label><br>
            <input type="text" class="form-control @error('username') border-danger @enderror" name="username" id="username" value="{{ $user->name }}" pattern="[a-zA-Z][a-zA-Z0-9-_(). ÀÂÇÉÈÊÎÔÛÙàâçéèêôûù]{3,15}" title="Username must be between 3 & 15 charcarters in length and containes only letters, numbers, and these symbols -_()">
            @error('username')
                <div class="text-danger">
                        {{ $message }}
                </div>
            @enderror   
        </div>
        
        <div class="form-group">
            <label for="email" class="font-weight-bold">Email :</label><br>
            <input type="email" class="form-control @error('email') border-danger @enderror" name="email" id="email" value="{{ $user->email }}">
            @error('email')
                <div class="text-danger">
                        {{ $message }}
                </div>
            @enderror   
        </div>
        

        <div class="form-group">
            <label for="phone_number" class="font-weight-bold">Phone Number :</label><br>
            <div class="d-flex">
                <span class="unity">+212</span>
                <input type="text" name="phone_number" class="form-control p-unity @error('phone_number') border-danger @enderror" id="phone_number" value="{{ substr($user->phone_number,3) }}" pattern="[0-9]{9}" title="Phone Number must be at least 9 numbers">
            </div>
            @error('phone_number')
                <div class="text-danger">
                        {{ $message }}
                </div>
            @enderror
        </div>
        
        <br>

        <div class="form-check d-flex justify-content-between p-0">
            
            <div>
                <h6 class="font-weight-bold">Receive email notifications</h6>
            </div>
            
            <div class="">
                @if ($user->notified)
                    <div class="" style="width: 35px">
                        <div class="check">
                            <input type="checkbox" id="rail" name="notified" value="{{$user->id}}" checked>
                        </div>
                    </div>
                @else
                    <div class="d-inline" style="width: 35px">
                        <div class="check">
                            <input type="checkbox" id="rail" name="notified" value="{{$user->id}}">
                        </div>
                    </div>
                @endif
            </div>
            <br><br>
        </div>

        
        <button type="submit" class="btn btn-primary w-100 ">Save Changes</button>
    </form> 
</div>
@endsection