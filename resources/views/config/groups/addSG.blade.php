@extends('layouts.app')

@section('content')

<div class="container p-3">

    <form action="{{ route('createSG') }}" method="get">
        <label for="hg_name"><b>Servicegroup Name <span class="text-danger">*</span></b></label>
        <input type="text" name="servicegroup_name" class="form-control w-50 @error('servicegroup_name') border-danger @enderror" id="hg_name" value="{{ old('servicegroup_name') }}">
        @error('servicegroup_name')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
        
        <br>
        
        <label for="mbrs"><b>Members <span class="text-danger">*</span></b></label>
        <br>
        @error('members')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror

        <div class="p-2 bg-white w-50" style="overflow: auto;max-height:200px;border:1px solid rgb(216, 215, 215);border-radius:5px">
        @forelse ($services as $service)
            <input type="checkbox" name="members[]" id="mbrs" value="{{$service->service_object_id}}"> {{$service->service_name}} <span class="text-secondary">({{$service->host_name}})</span>
            <br>
        @empty
            <p>No services</p>
        @endforelse
        </div>
        <br>

        <button class="btn btn-primary">Add</button>

    </form>

</div>

@endsection