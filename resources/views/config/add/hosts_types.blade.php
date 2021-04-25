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

</style>

<div class="container p-3 text-center">
    <h4>Choose type of Host for monitoring </h4>
</div>

<div class="container w-100 d-flex justify-content-center align-items-center" style="height: 50vh">

    <a href="{{ route('manageHost', ['type' => 'windows']) }}" class="btn btn-outline-primary item">Windows</a>
    <a href="{{ route('manageHost', ['type' => 'linux']) }}" class="btn btn-outline-primary item">Linux</a>
    <a href="{{ route('manageHost', ['type' => 'switch']) }}" class="btn btn-outline-primary item">Switch</a>
    <a href="{{ route('manageHost', ['type' => 'router']) }}" class="btn btn-outline-primary item">Router</a>
    <a href="{{ route('manageHost', ['type' => 'printer']) }}" class="btn btn-outline-primary item">Printer</a>

</div>

@endsection