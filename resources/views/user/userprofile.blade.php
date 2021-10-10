@extends('layouts.app')

@section('content')

<style>

.popup{
    display: none;
    width: 350px;
    position: absolute;
    top: 30%;
    left: 50%;
    transform: translate(-50%, -50%);
}

</style>

<div class="container back">

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
        <h4 class="float-left m-0">My Profile</h4>
        <div class="float-right">

            <a href="{{ route('edit-info') }}" class="d-inline text-info p-2"><i class="fas fa-pen"></i></a>
            
            @if ($userProfile->id != 1)
                <button type="submit" class="btn text-danger remove"><i class="fas fa-trash"></i></button>
            @endif
            
        </div>
    </div>
    


    <div class="card-body">
        
        <div class="float-left mx-4">
            <h5 class="py-2 font-weight-bold" >Username :</h5>
            <h5 class="py-2 font-weight-bold" >Email :</h5>
            <h5 class="py-2 font-weight-bold" >Phone Number :</h5>
            <h5 class="py-2 font-weight-bold" >User Type :</h5>
            <h5 class="py-2 font-weight-bold" >Notified :</h5>
            <h5 class="py-2 font-weight-bold" >Member Since :</h5>
        </div>

        <div class="flot-right mx-4 text-primary">
            <h5 class="py-2" >{{ $userProfile->name }}</h5>
            <h5 class="py-2" >{{ $userProfile->email }}</h5>
            <h5 class="py-2" >+{{ $userProfile->phone_number }}</h5>

            @if ($userProfile->hasRole('agent'))
                <h5 class="py-2" >Agent</h5>
            @else
                <h5 class="py-2" >Superviseur</h5>
            @endif
            
            @if ($userProfile->notified)
                <h5 class="py-2 text-success" >Yes</h5>
            @else
                <h5 class="py-2 text-danger" >No</h5>
            @endif
            
            <h5 class="py-2" >{{ $userProfile->created_at }}</h5>
        </div>

    
    </div>
    
</div>

</div>

<div class="container p-3 bg-white shadow rounded text-center popup">
    <h6><b>Are you sure?</b></h6>
    <P>Are you sure you want to delete your account? If you delete it there's no going back.</P>
    <form action="{{ route('deleteMyAccount', $userProfile->id) }}" method="post" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete</button>
    </form>

    <button type="submit" class="btn btn-light border border-secondary d-inline cancel" >Cancel</button>
</div>

<script>

const remove = document.querySelector('.remove');
const cancel = document.querySelector('.cancel');
const popup = document.querySelector('.popup');
const back = document.querySelector('.back');

remove.onclick = () => {
    popup.style.display = 'block';
    back.style.opacity = '.2';
}

cancel.onclick = () => {
    popup.style.display = 'none';
    back.style.opacity = '1';
}

</script>

@endsection