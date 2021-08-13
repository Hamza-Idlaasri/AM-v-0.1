@extends('layouts.app')

@section('content')

    <h4 class="text-secondary text-center"><i>Choose box</i></h4>
    
    <div class="d-flex flex-wrap justify-content-around border">

        @for ($i = 0; $i < 10; $i++)
            @foreach ($boxs as $box)
                <a href="{{ route('selectBox', $box->box_id) }}" class="btn btn-outline-primary m-1 w-50"><b>{{ $box->box_name }}</b></a>    
            @endforeach
        @endfor
        
    </div>

@endsection