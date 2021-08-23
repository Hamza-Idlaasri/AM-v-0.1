@extends('layouts.app')

@section('content')

    <h5 class="text-muted text-center my-2"><i>Choose box</i></h5>
    
    <div class="d-flex flex-wrap justify-content-around">
        
        @foreach ($boxs as $box)
            <a href="{{ route('selectBox', $box->box_id) }}" class="btn btn-outline-primary py-3 m-1" style="width: 35%"><b>{{ $box->box_name }}</b></a>    
        @endforeach
        
    </div>

@endsection