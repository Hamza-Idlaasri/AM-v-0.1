@extends('layouts.app')

@section('content')

<div class="container p-3">

    <form action="{{ route('createHG') }}" method="get">
        <label for="hg_name"><b>Hostgroup Name <span class="text-danger">*</span></b></label>
        <input type="text" name="hostgroup_name" class="form-control w-50 @error('hostgroup_name') border-danger @enderror" id="hg_name" value="{{ old('hostgroup_name') }}">
        @error('hostgroup_name')
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
        @forelse ($hosts as $host)
            <input type="checkbox" name="members[]" id="mbrs" value="{{$host->host_name}}"> {{$host->host_name}}
            <br>
        @empty
            <p>No hosts</p>
        @endforelse

        <br>

        <button class="btn btn-primary">Add</button>

    </form>

</div>

@endsection