@extends('layouts.app')

@section('content')

<div class="container">

<div class="container my-3">
    <div class="container w-50">
        @if (session('status'))
            <div class="alert alert-success text-center">
                {{ session('status') }}
            </div>
        @endif
    </div>
</div>

<div class="card m-3">
    <div class="card-header">
        <h3 class="float-left">My Profile</h3>
        <div class="float-right">
            
            <a href="{{ route('edit-info') }}" class="d-inline text-info p-2"><i class="fas fa-pen"></i></a>
            
            <form action="{{ route('deleteMyAccount', $userProfile->id) }}" method="post" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn text-danger"><i class="fas fa-trash"></i></button>
            </form>
            
        </div>
    </div>
    


    <div class="card-body">
        
        <div class="float-left mx-4">
            <h5 class="py-2 font-weight-bold" >Username :</h5>
            <h5 class="py-2 font-weight-bold" >Email :</h5>
            <h5 class="py-2 font-weight-bold" >User Type :</h5>
            <h5 class="py-2 font-weight-bold" >Member Since :</h5>
        </div>

        <div class="flot-right mx-4 text-primary">
            <h5 class="py-2" >{{ $userProfile->name }}</h5>
            <h5 class="py-2" >{{ $userProfile->email }}</h5>

            @if ($userProfile->hasRole('agent'))
                <h5 class="py-2" >Agent</h5>
            @else
                <h5 class="py-2" >Superviseur</h5>
            @endif
            
            <h5 class="py-2" >{{ $userProfile->created_at }}</h5>
        </div>

    
    </div>
    
</div>

</div>

@endsection