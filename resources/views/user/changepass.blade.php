@extends('layouts.app')

@section('content')
    <div class="container my-5 w-25 bg-white shadow p-3">

        @if (session('status'))
            <div class="alert alert-danger text-center">
                {{ session('status') }}
            </div>
        @endif

       <form action="{{ route('changePassword') }}" method="post">
            @csrf
            @method('PUT')
           
            <div class="form-group">
                <label for="oldPassword">Old Password :</label><br>
                <input type="password" class="form-control @error('oldPassword') border-danger @enderror" name="oldPassword" id="oldPassword">
                @error('oldPassword')
                    <div class="text-danger">
                            {{ $message }}
                    </div>
                @enderror   
            </div>
            
        
            <div class="form-group">
                <label for="newPassword">New Password :</label><br>
                <input type="password" class="form-control @error('password') border-danger @enderror" name="password" id="newPassword">
                @error('password')
                    <div class="text-danger">
                            {{ $message }}
                    </div>
                @enderror   
            </div>
            
            <div class="form-group">
                <label for="password_confirmation">Confirme New Password :</label><br>
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
            </div>


            <button type="submit" class="btn btn-primary w-100 ">Save Changes</button>
        </form> 
    </div>
    
@endsection