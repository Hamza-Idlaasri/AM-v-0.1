@extends('layouts.app')

@section('content')
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
            <label for="username">Username :</label><br>
            <input type="text" class="form-control @error('username') border-danger @enderror" name="username" id="username" value="{{ $user->name }}" pattern="[a-zA-Z][a-zA-Z0-9 ]{3,15}" title="Username must be between 3 & 15 charcarters in length and containes only letters, numbers">
            @error('username')
                <div class="text-danger">
                        {{ $message }}
                </div>
            @enderror   
        </div>
        
        <div class="form-group">
            <label for="email">Email :</label><br>
            <input type="email" class="form-control @error('email') border-danger @enderror" name="email" id="email" value="{{ $user->email }}">
            @error('email')
                <div class="text-danger">
                        {{ $message }}
                </div>
            @enderror   
        </div>
        
        <button type="submit" class="btn btn-primary w-100 ">Save Changes</button>
    </form> 
</div>
@endsection