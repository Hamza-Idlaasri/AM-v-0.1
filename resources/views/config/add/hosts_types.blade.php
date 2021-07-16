@extends('layouts.app')

@section('content')

<style>

.item{
    width: 100px;
    height: 100px;
    border-radius:100%;
    font-weight: bold;
    font-size: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 20px;
    border:3px solid;
    padding: 10px;
}

.switch img:hover{
   filter: brightness(500%);
}

.router img:hover{
   filter: brightness(500%);
}

.windows img:hover{
   filter: brightness(500%);
}



</style>

<div class="container p-3 text-center">
    <h5 class="text-muted"><i>Choose type of Host for monitoring</i></h5>
</div>

<div class="container w-100 d-flex justify-content-center align-items-center" style="height: 50vh">

    <div class="d-flex flex-column justify-content-center align-items-center windows">
        <a href="{{ route('manageHost', ['type' => 'windows']) }}" class="btn btn-outline-primary item">
            <img src="{{ asset('images/interface/windows.png') }}" alt="">
        </a>
        <h5 class="text-primary font-weight-bolder">Windows</h5>
    </div>

    <div class="d-flex flex-column justify-content-center align-items-center">
        <a href="{{ route('manageHost', ['type' => 'linux']) }}" class="btn btn-outline-primary item"><i class="fab fa-linux fa-2x"></i></a>
        <h5 class="text-primary font-weight-bolder">Linux</h5>
    </div>

    <div class="d-flex flex-column justify-content-center align-items-center switch">
        
        <a href="{{ route('manageHost', ['type' => 'switch']) }}" class="btn btn-outline-primary item">
            <img src="{{ asset('images/interface/switch.png')}}" alt="">
        </a>
        <h5 class="text-primary font-weight-bolder">Switch</h5>
    </div>

    <div class="d-flex flex-column justify-content-center align-items-center router">
        <a href="{{ route('manageHost', ['type' => 'router']) }}" class="btn btn-outline-primary item">
            <img src="{{ asset('images/interface/wifi-router.png') }}" alt="">
        </a>
        <h5 class="text-primary font-weight-bolder">Router</h5>
    </div>

    <div class="d-flex flex-column justify-content-center align-items-center">
        <a href="{{ route('manageHost', ['type' => 'printer']) }}" class="btn btn-outline-primary item"><i class="far fa-print fa-2x"></i></a>
        <h5 class="text-primary font-weight-bolder">Printer</h5>
    </div>

</div>

@endsection